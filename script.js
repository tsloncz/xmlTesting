function createXml(){
    uploadType = 'string';
    xmlString = '';
    console.log("Begin parsing page into XML WOOHOO");
    xmlString = xmlString + "<"+$('.fileType').attr("id")+">";
    $( ".element" ).each(function() {
        var id = $(this).attr('id');
        if( id === "attributeElement")
        {
            xmlString = attributeElement($(this).attr("name"), xmlString, id);
            xmlString = xmlString +  "</"+ $(this).attr("name")+">" ;
        }
        else if( id === "dataInChildrenElement")
        {
            xmlString = xmlString +  "<"+ $(this).attr("name")+">" ;
            xmlString = attributeElementwithChildData( $(this).attr("name"), xmlString, id);
            xmlString = xmlString +  "</"+ $(this).attr("name")+">" ;
        }
        else if( id === "attributeElementwithChildData")
        {
            xmlString = attributeElement($(this).attr("name"), xmlString, id);
            xmlString = attributeElementwithChildData( $(this).attr("name"), xmlString, id);
            xmlString = xmlString +  "</"+ $(this).attr("name")+">" ;
        }
        else if( id === "csvElement")
        {
            xmlString = xmlString +  "<"+$(this).attr("name")+">";
            xmlString = csvElement( $(this).attr("name"), xmlString, id);
            xmlString = xmlString +  "</"+ $(this).attr("name")+">";
        }
        else if( id === "csvTableElement")
        {
            xmlString = xmlString +  "<"+$(this).attr("name")+' Columns="';
            xmlString = csvTableElement( $(this).attr("name"), xmlString, id);
            xmlString = xmlString +  "</"+ $(this).attr("name")+">";
        }
        else
        {
            xmlString = xmlString + "<"+ $(this).attr("name")+" id='"+$(this).attr('id')+ "'>" ;
            xmlString = xmlString +  "</"+ $(this).attr("name")+">" ;
        }
       });
    xmlString = xmlString + "</"+$('.fileType').attr('id')+">";
    //console.log(xmlString);
    $.post( "createXml.php" , {xmlString:xmlString,uploadType:uploadType})
        .done( function(data){
                //console.log('data' + data);
                console.log('SUCCESS!');
        });
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
                    xml = xml + "></"+element+">";
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
        if( $(this).attr("id") === name)
        {
            console.log("found table for " + name);
            $(this).find('th').each(function (i) {
                if(i>0)
                {
                    xml = xml + ",";
                }
                xml = xml + $(this).text();
            });
            xml = xml +'">';
            $(this).find('tr').each(function (i) {
                if(i>0)
                {
                    $(this).find('td').each(function (i) {
                        if(i>0)
                        {
                            xml = xml + ",";
                        }
                        $(this).find('input').each(function () {
                            xml = xml +   $(this).val();
                        });
                    });
                }
                xml = xml + ",\n";
            });
        }
    });
    return xml;
}