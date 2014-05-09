// Event handlers
// A $( document ).ready() block.
$( document ).ready(function() {
	validFileName();
    console.log( "ready!" );
	$( "#fileName" ).change(function() {
	  validFileName();
	});
});
function validFileName()
{
	var suffix = $('.fileType').attr("id").concat(".xml");
	var fileName = cleanName($( "#fileName" ).val());
	$( "#validFile" ).text(fileName.concat("."+suffix));
}
function cleanName(name) {
    name = name.replace(/\s+/gi, '-'); // Replace white space with dash
    return name.replace(/[^a-zA-Z0-9\-]/gi, ''); // Strip any special charactere
};

var lastElement = -1;
function createXml(){
	var fileName = $( "#validFile" ).text();
    console.log("createXml called");
    uploadType = 'string';
    xmlString = '';
    
    xmlString = xmlString + "<"+$('.fileType').attr("id")+' type="fileType">\n';
    console.log("element: " + $( ".fileType" ).attr("id") +" has "+$(".fileType").children(".element").length+" children\n ");
    console.log("above each");
    var rootElement = new element($( ".fileType" ).attr("id"), $(".fileType").children(".element").length, "root");
    
    // Handle all elements
    xml = handleElement( $( ".fileType" ).children(".element"), xmlString );
    var res = xmlString.concat(xml);
    console.log("After Each");
    xmlString = res.concat("</"+$('.fileType').attr('id')+">");
    console.log(rootElement.numberOfChildren());
    //Send xml string to server for file creation
    $.post( "createXml.php" , {xmlString:xmlString,uploadType:uploadType, fileName:fileName})
        .done( function(data){
                //console.log('data' + data);
                console.log('SUCCESS!');
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

function handleElement( element )
{
    var tempString = '';
    var xml = '';
    $.each( element, function(i){
        var temp = $(this);
        xml = xml.concat(handleChild( temp ) );
    });
    return xml;
}
function handleChild( element )
{
    //var kids = element.children(".element");
    var temp = element;
    var tempString = '';
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
    }
    
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