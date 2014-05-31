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
	//Ajax file upload initialize
	$('#uploadForm').ajaxForm(function() { 
                console.log("Thank you for your comment!"); 
    			getUserFiles();
				$(this).ajaxSubmit({
        target: 'myResultsDiv'
    	})
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
		//Convert string to JSON format and send to be printed ot screen
        printUserFiles( jQuery.parseJSON(data) );
    });
}
// Passes filename to PHP and PHP copies it to users simulation folder
function addToSimulation(element)
{
	var fileName = element.attr('id');
    $.post( "handler.php", { cmd: "addToSimulation", fileToAdd:fileName} )
    .done( function(data){
		//refresh user files list
        getUserFiles();
    });
}
function runSimulation()
{
	// File types required to run simulation
	var required = ["cdb","wdb","sdb","xdb","gdb"];
	var types = [];
	var missingTypes = [];
	// Create list of all types user has in simulation folder
	$.each( $(".userSimulationFiles").children("li"), function(){
		var type = $(this).html().split(".");
		var typeElement = type.length -2;
		type = type[typeElement];
		types.push(type);
	});
	// Check if user has all required types in simulation folder
	$.each(required, function(i){
		//If required type is not in array add it to missingTypes
		if( types.indexOf( required[i] ) == -1)
		{
			missingTypes.push(required[i]);
		}
	});
	// Alert user to missing types
	if( missingTypes.length > 0 )
	{
		var missing = "Missing file types: <br />";
		$.each(missingTypes, function(i){
			missing += missingTypes[i] + "<br />";
			console.log( missingTypes[i] + "<br />");
		});
		missing += "Please add them and try again";
		// Append message to alert dialog and open it
		$( "#dialog-alert" ).children("p").empty().append(missing);
		$( "#dialog-alert" ).dialog({
		  height: 140,
		  modal: true
		});

	}
}
function deleteFile(file)
{
	var fileName = file.parent().attr("id");
	// Open confirm dialog to make sure user wishes to delete
	// Cuz I'm nice :)
	if( fileName.search("template_") != -1 )
	{
		var message = "Template files cannot be deleted";
		$( "#dialog-alert" ).children("p").empty().append(message);
		$( "#dialog-alert" ).dialog({
		  height: 140,
		  modal: true
		});
	}
	else
	{
		$( "#dialog-confirm" ).dialog({
		  resizable: false,
		  height:140,
		  modal: true,
		  buttons: {
			"Delete": function() {
			  $( this ).dialog( "close" );
				file = file.next('a').attr('id');
				$.post( "handler.php", { cmd: "deleteFile", fileName:file} )
				.done( function(data){
					getUserFiles();
				});
			},
			Cancel: function() {
			  $( this ).dialog( "close" );
			}
		  }
		});
	}
}
/*	Prints users files and simulation files
*	to proper locaitons
*/
function printUserFiles( files )
{
	var userFiles = files[0].userFiles;
	userFiles.sort();
	var userSimulationFiles = files[1].userSimulationFiles;
	console.log("userFiles " + userSimulationFiles );
	$(".userFiles").empty();
    userFiles.forEach( function(fileName){
		// Add icons and filename
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
	var temp = element.parent();
	var tab = temp.parent();
	var type = element.parent().attr("id");
	var lastListElement = tab.children("ul").find("li").last().attr("aria-controls" );
	newTabNum = parseInt(lastListElement.split("_").pop() ) + 1;
	var newTabName = element.parent().attr("id" ).split("_",1);
	var newTabId = newTabName+ "_" + newTabNum;
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
            });
			
            validFileName();
    });
} 
function removeTab(element)
{
	if( element.parent().parent().children("li").length ==1 )
	{
		var message = "Sorry this tab cannot be deleted. This element requires at least one tab.";
		$( "#dialog-alert" ).children("p").empty().append(message);
		$( "#dialog-alert" ).dialog({
		  height: 140,
		  modal: true
		});

	}
	else
	{
		  var panelId = element.closest( "li" ).remove().attr( "aria-controls" );
		  $( "#" + panelId ).remove();
	}
}