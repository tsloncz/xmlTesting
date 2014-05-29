// Event handlers
// A $( document ).ready() block.
/* Handles saving of the files
	Creates XML string 
	Sends it to server to be converted to xml file
	and saved
*/
$( document ).ready(function() {
	//validFileName();
    console.log( "ready!" );
	$( "#fileName" ).change(function() {
	  validFileName();
	});
});

var lastElement = -1;
/*
*	create root element
* 	Pass all children to handleElement
*	Create elements attributes
*	Pass all children to handleElement
*/
function createXml(){
	var fileName = $( "#validFile" ).text();
    uploadType = 'string';
    xmlString = '';
    // Create root tag
    xmlString = xmlString + "<?xml version='1.0' encoding='utf-8'?><"+$('.fileType').attr("id")+'>\n';
    // Handle all elements
    xml = handleElement( $( ".fileType" ).children("#tabs").children(".element"), xmlString );
    var res = xmlString.concat(xml);
    xmlString = res.concat("</"+$('.fileType').attr('id')+">");
    //Send xml string to server for file creation
    $.post( "handler.php" , {cmd: "saveFile", xmlString:xmlString,uploadType:uploadType, fileName:fileName})
        .done( function(data){
                //console.log('data' + data);
                console.log('SUCCESS!');
				console.log(data);
		getUserFiles();
        });
}

//element object
function element(name, children, type )
{
this.name = name;
this.children = children;
this.type = type;
this.children=[];
this.addChild = function(child)
                {
                    this.children.push(child);
                };
                
this.numberOfChildren = function(){return this.children.length;};
}
// Takes in array of 1st level children
function handleElement( element )
{
	var base = "<"+element.attr("name")+"></"+element.attr("name")+">" ;
    var tempString = '';
    var xml = '';
    $.each( element, function(){
        xml = xml.concat("<"+$(this).attr("name"));
		if( $(this).children(".headers").length )
		{
        	xml = xml.concat(handleCsv( $(this) ) );
		}
		else
		{
			xml = xml.concat( getAttributes($(this).children(".attributes").children(".attribute") ));
			xml = xml.concat(">");
			xml = xml.concat( $(this).children(".elementData").children('input[type=text], textarea').val() );
			xml = xml.concat(handleElement( $(this).children("#tabs").children(".element") ) );
		}
			xml = xml.concat("</"+$(this).attr("name")+">");
    });
    return xml;
}
function getAttributes( element )
{
	var tempString = '';
	$.each( element, function(){
		tempString = tempString.concat(" "+$(this).children('b').html()+'="'+$(this).children('input').val()+'"');
	});
	return tempString;
}
function handleCsv( element )
{
	var temp = ' columns="';
	var numColumns = element.children(".headers").children(".heading").length;
	//Add attributes
	$.each( element.children(".headers").children(".heading"), function(){
		temp = temp.concat($(this).html() + ",");
	});
	temp = temp.concat('">');
	//Add data
	$.each( element.children(".values").children(".value").children('input'), function(i){
		if(i>0 && i%numColumns === 0)
		{
			console.log("new row \n");
			temp.concat("\n");
		}
		temp = temp.concat($(this).val() + ",");
	});
	return temp;
}
function handleChild( element )
{
    //var kids = element.children(".element");
    var temp = element;
    var tempString = "<"+element.attr("name")+"></"+element.attr("name")+">" ;
		console.log("Handleing " + element.attr("name"));
		/*
    var type = temp.attr('type');
    var name =temp.attr('name');
    var hasAttributes = temp.attr('attributes');
    console.log(name+" has "+hasAttributes +" attributes");
    switch (type)
    {
        case "wrapper":
            tempString = tempString.concat(handleWrapper( element ) );
            break;
        case "uniformChildData":
            tempString = tempString.concat(handleUniformChildData( element ) );
            break;
        case "heteroChildData":
            tempString = tempString.concat("<"+name+'>\n');
            tempString = tempString.concat(getHeteroChildData( element ) );
            tempString = tempString.concat("</"+name+">\n");
            break;
        case "stringData":
            tempString = tempString.concat(handleStringData( element ) );
            break;
        case "csvData":
            tempString = tempString.concat( getCsvData( element ) );
            break;
    }*/
    
    return tempString;
}
function handleWrapper( element )
{
    var type = element.attr('type');
    var name =element.attr('name');
    var hasAttributes = element.attr('attributes');
    var tempString = '';
    if(hasAttributes === '1')
    {
        tempString = tempString.concat("<"+name);
        tempString = tempString.concat( getAttributeTable( element.children(".attributeTable") ) );
    }
    else
    {
        tempString = tempString.concat("<"+name+'>\n');
    }
    tempString = tempString.concat(handleElement( element.children(".element") ) );
    tempString = tempString.concat("</"+name+">\n");
    return tempString;
    
}
function handleUniformChildData( element )
{
    var type = element.attr('type');
    var name =element.attr('name');
    var hasAttributes = element.attr('attributes');
    var tempString = '';
    if(hasAttributes === '1')
    {
        tempString = tempString.concat("<"+name);
        tempString = tempString.concat( getAttributeTable( element.children(".attributeTable") ) );
    }
    else
    {
        tempString = tempString.concat("<"+name+'>\n');
    }
    tempString = tempString.concat( getUniformChildData( element.children(".uniformDataTable") ) );
    tempString = tempString.concat("</"+name+">\n");
    return tempString;
    
}
function getAttributeTable( element )
{
    var tempString = '';
    element.find('td').each(function () {
            attribute = $(this).attr("id");
            $(this).find('input').each(function () {
                tempString = tempString.concat(" "+ attribute +'="'+$(this).val()+'"');
            });
        });
    tempString = tempString.concat('>\n');
    return tempString;
    
}
function getUniformChildData( element )
{
    var tempString = '';
    var name =element.attr('name');
    element.find('tr').each(function (i) {
        if(i>0)
        {
            tempString = tempString.concat("<"+name+" ");
            $(this).find('td').each(function () {
                    attribute = $(this).attr("id");
                    $(this).find('input').each(function () {
                        tempString = tempString.concat(attribute+'="'+ $(this).val()+'" ');
                        });
            });
                    tempString = tempString.concat("></"+name+">\n");
        }
    });
    return tempString;
}
function getHeteroChildData( element )
{
    console.log("Getting heteroChildData");
    var tempString = '';
    var name =element.attr('name');
    tempString = tempString.concat(handleElement( element.children(".element") ) );
    return tempString;
}
function handleStringData( element )
{
    var type = element.attr('type');
    var tempString = '';
    var name =element.attr('name');
    var hasAttributes = element.attr('attributes');
    var tempString = '';
    var content = element.children("textArea");
    console.log("content: "+content.val());
    if(hasAttributes === '1')
    {
        tempString = tempString.concat("<"+name+'"');
        tempString = tempString.concat( getAttributeTable( element.children(".attributeTable") ) );
    }
    else
    {
        tempString = tempString.concat("<"+name+'>\n');
    }
    tempString = tempString.concat( content.val() );
    tempString = tempString.concat("</"+name+">\n");
    return tempString;
}
function getCsvData( element )
{
    console.log("Getting CsvData");
    var tempString = '';
    var name =element.attr('name');
    tempString = tempString.concat("<"+name+' Columns="');
    // Add headings as element attribute value
    var numColumns = element.find('th').length;
    console.log("Columns: "+numColumns);
    element.find('th').each(function () {
            console.log($(this).html());
            tempString = tempString.concat($(this).html()+",");
    });
    tempString = tempString.concat('">\n');
    // Add csv data from table
    element.find('input').each(function (i) {
        i++;
        tempString = tempString.concat($(this).val()+',');
        if(i>0 &&i%numColumns == 0)
            tempString = tempString.concat("\n");
    });
    
    return tempString.concat("</"+name+">\n");
}
var table = -1;
function attributeElement( name, xml, id)
{
    var foundTable = 0;
    xml = xml + "<"+ name +" id='"+ id +"'";
    $(".attributeTable").each(function(i){
        //console.log("i: "+i+" table: "+table);
        if( $(this).attr("id") === name && i > table )
        {
            console.log(i);
            table = i;
            foundTable = 1;
            //console.log("found attribute table for " + name);
            $(this).find('td').each(function () {
                attribute = $(this).attr("id");
                $(this).find('input').each(function () {
                    xml = xml + " "+ attribute +'="'+$(this).val()+'"';
                });
            });
        }
        if(foundTable === 1)
            return false;
    });
    xml = xml + ">";
    return xml;
}

function attributeElementwithChildData( name, xml, id )
{
    $(".childData").each(function(){
        if( $(this).attr("id") === name)
        {
            element = $(this).attr("name");
            //console.log("found childData table for " + name + "with elements" + element);
            $(this).find('tr').each(function (i) {
                if(i>0)
                {
                    xml = xml + "<"+element+" ";
                    $(this).find('td').each(function () {
                        attribute = $(this).attr("id");
                        $(this).find('input').each(function () {
                            xml = xml + attribute+'="'+ $(this).val()+'" ';
                        });
                    });
                    xml = xml + "></"+element+">\n";
                }
            });
        }
    });
    return xml;
}

var csvData = -1;
function csvElement( name, xml, id )
{
    var found = 0;
    $(".csvData").each(function(i){
        console.log("i: "+i+" csvData: "+csvData);
        if( $(this).attr("id") === name && i > csvData)
        {
            csvData = i;
            found = 1;
            $(this).find('input').each(function () {
                xml = xml +  $(this).val();
            });
        }
        if(found === 1)
        {
            return false;
        }
    });
    return xml;
}

var csvTableData = -1;
function csvTableElement( name, xml, id )
{
    $(".csvTable").each(function(){
        var rowHadData = 0;
        if( $(this).attr("id") === name)
        {
            console.log("found table for " + name);
            $(this).find('th').each(function (i) {
                xml = xml + $(this).text() + ",";
            });
            xml = xml +'">\n';
            $(this).find('tr').each(function (i) {
                if(i>0)
                {
                    $(this).find('td').each(function (i) {
                        if(i>0)
                        {
                            //xml = xml + ",";
                        }
                        $(this).find('input').each(function () {
                            rowHadData = 1;
                            xml = xml +   $(this).val() + ",";
                        });
                    });
                }
                if( rowHadData === 1)
                {
                    xml = xml + "\n";
                    rowHadData = 0;
                }
            });
        }
    });
    return xml;
}