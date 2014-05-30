// utilities.js handles fun...ctionality :)
$( document ).ready(function() {
    getUserFiles();
	$( "#dialog-confirm" ).hide();
	$( "#dialog-alert" ).hide();
	$( "#tabs").delegate( "span.ui-icon-close", "click", function() {
      removeTab();
	});
	$( "#fileName" ).change(function() {
	  validFileName();
	});
	$('#uploadForm').ajaxForm(function() { 
                console.log("Thank you for your comment!"); 
    			getUserFiles();
				$(this).ajaxSubmit({
        target: 'myResultsDiv'
    	})
   	});
	var dialog = $( "#dialog" ).dialog({
      autoOpen: false,
      modal: true,
      buttons: {
        Add: function() {
          addTab();
          $( this ).dialog( "close" );
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
      }
    });
	var form = dialog.find( "form" ).submit(function( event ) {
      newTab();
      dialog.dialog( "close" );
      event.preventDefault();
    });
	
});
function validFileName()
{
	var suffix = new String($('.fileType').attr("id")).toLowerCase().concat(".xml");
	var fileName = cleanName($( "#fileName" ).val());
	$( "#validFile" ).text(fileName.concat("."+suffix));
}
function cleanName(name) {
    name = name.replace(/\s+/gi, '_'); // Replace white space with underscore
    return name.replace(/[^a-zA-Z0-9\-]/gi, ''); // Strip any special charactere
};
function clearValues(element)
{
	//console.log("Clearing values for " + element.parent().attr("id") );
	element.children('.attribute').children('input:text').val('');
}
    
function getUserFiles()
{
    $.post( "handler.php", { cmd: "printUserFiles"} )
    .done( function(data){
		console.log(jQuery.parseJSON(data) );
        printUserFiles( jQuery.parseJSON(data) );
    });
}
function addToSimulation(element)
{
	var fileName = element.attr('id');
    $.post( "handler.php", { cmd: "addToSimulation", fileToAdd:fileName} )
    .done( function(data){
		console.log(data);
        getUserFiles();
    });
}
function deleteFile(file)
{
	$( "#dialog-confirm" ).dialog({
      resizable: false,
      height:140,
      modal: true,
      buttons: {
        "Delete": function() {
          $( this ).dialog( "close" );
			file = file.next('a').attr('id');
			//console.log("deleting " +file);
			$.post( "handler.php", { cmd: "deleteFile", fileName:file} )
			.done( function(data){
				//console.log(data);
				getUserFiles();
			});
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
}
function printUserFiles( files )
{
	var userFiles = files[0].userFiles;
	var userSimulationFiles = files[1].userSimulationFiles;
	console.log("userFiles " + userSimulationFiles );
	$(".userFiles").empty();
    userFiles.forEach( function(fileName){
       $(".userFiles").append("<li id='"+fileName+"'><a class='addToSim' onclick='addToSimulation($(this).parent())'>"+
	   "<img  href='#' title='Add file to Simulation' src='green_plus_sign.png' align='bottom' width='16' height='16'></a>"+
	   "&nbsp &nbsp<a class='dwnLd' href='download.php?fileToDownload="+fileName+"'>"+
	   "<img  href='#' title='Download File' src='download.png' align='bottom' width='22' height='22'></a>"+
	   "&nbsp &nbsp<span class='deleteFile' href='#' title='Delete File' onclick='deleteFile($(this))'>&#935</span>"+
	   "&nbsp &nbsp<a href='#' title='Edit File' class='fileName' id='"+fileName+"' onclick='createInterface( $(this) )'>" +
	    fileName + "</a></li>"); 
    });
	$(".userSimulationFiles").empty();
    userSimulationFiles.forEach( function(fileName){
       $(".userSimulationFiles").append("<li>"+fileName+"</li>"); 
    });
}
// Downloads file
function downloadFile(element)
{
	var fileName = element.attr('id');
	//console.log("download: " + fileName);
	$.post( "handler.php", { cmd: "downloadFile", fileToDownload:fileName} )
	.done(function (data) { alert(data +'File download a success!'); })
    .fail(function () { alert('File download failed!'); });
	
}
function uploadFile()
{
	console.log("Uploading file");
}
function newTab( element )
{
	var newDivHtml = element.parent().html();
	//console.log(newDivHtml);
	//elementClone = elementClone.html();
	var temp = element.parent();
	var tab = temp.parent();
	var type = temp.attr("id");
	var lastListElement = tab.children("ul").find("li").last().attr("aria-controls" );
	//console.log("Last list item: " + lastListElement);
	newTabNum = parseInt(lastListElement.split("_").pop() ) + 1;
	var newTabName = lastListElement.split("_",1);
	var newTabId = newTabName+ "_" + newTabNum;
	//console.log("New tab: " + newTabName +"_"+ newTabNum);
	// Add link to new tab to list
	tab.children("ul").append("<li><a href='#" + newTabId + "'>" + newTabId + "</a><span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>");
	var newDiv ="<div class='element' id='"+newTabId+"' name='"+newTabName+"'>";
	newDiv = newDiv.concat(newDivHtml);
	newDiv = newDiv.concat("</div>");
	// Add newtab div to end of current tabs divs
	tab.append(newDiv);
	tab.tabs( "refresh" );
}
// Creates the interface for the file
function createInterface(file)
{
    file = file.attr("id");
    console.log("Creating interface for " + file );
    $.post( "handler.php", { cmd: "createInterface", filename: file} )
        .done( function(data){
            $("#editFileArea").empty().append(data);
            //Initialize all tabs
            $( "#tabs[id*='tab']" ).each(function(){
                $(this).tabs();
				$(this).delegate( "span.ui-icon-close", "click", function() {
				  var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
				  $( "#" + panelId ).remove();
				});
            });
			
            validFileName();
    });
} 
function removeTab()
{
	console.log("deleting");
    tabs.delegate( "span.ui-icon-close", "click", function() {
		if( $(this).parent().children("li").length ==1 )
		{
			$( "#dialog-alert" ).dialog({
			  height: 140,
			  modal: true
			});

		}
		else
		{
			  var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
			  $( "#" + panelId ).remove();
			  tabs.tabs( "refresh" );
		}
    });
}
// close icon: removing the tab on click
/*
function addTabClose()
{
    tabs.delegate( "span.ui-icon-close", "click", function() {
		if( $(this).parent().children("li").length ==1 )
		{
			$( "#dialog-alert" ).dialog({
			  height: 140,
			  modal: true
			});

		}
		else
		{
			  var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
			  $( "#" + panelId ).remove();
			  tabs.tabs( "refresh" );
		}
    });
}*/