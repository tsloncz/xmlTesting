// utilities.js handles fun...ctionality :)

//soil file object
function sdb()
{
	this.versionControl = new versionControl();
	this.soils = [];
	this.addSoil = addSoil;
	
	function addSoil()
	{
		soils.push(new soil);
	}

	function display()
	{
		return "<div class='sdb'>";
	}
}
	
function versionControl()
{
	this.version = 0;
	this.releaseDate = "";
	this.notes = "";
}

function soil()
{
	this.soilId = "";
	this.SIDesc = "";
	this.SIsour = "";
	this.layers = [];
	this.addLayer = addLayer;
	
	function addLayer()
	{
		layers.push(new layer);
	}
}

function layer()
{
	this.MH = "";
	this.ZLYR= "";
	this.LL = "";
}
 function duplicate( element )
{
	var newDiv = element.html();
	var temp = element.parent();
	var type = element.attr("id");
	console.log("creating new: " + type );
	if(type === 'soil')
	{
		var soilDiv="<div class='element' id='soil'>" +
			" new soil div" +
"<button onclick='deleteElement( $(this).parent() )'>delete soil</button>" +
"<button onclick='duplicate( $(this).parent() )'>new soil</button><br />" +
"<div class='element' id='layer'>" +
			"  new layer div " +
"<button onclick='deleteElement( $(this).parent() )'>delete layer</button>" +
"<button onclick='duplicate( $(this).parent() )'>new layer</button></div></div>";
		$(temp).append( newSoil() );
	}
	else if(type === 'layer')
	{
		var layerDiv="<div class='element' id='layer'>" +
			"new layer div" +
"<button onclick='duplicate( $(this).parent() )'>new layer</button>" +
"<button onclick='deleteElement( $(this).parent() )'>delete layer</button><br /></div>";
		$(temp).append(newLayer());
	}
};

function deleteElement( element )
{
	element.empty();
}

function newLayer()
{
	var layerDiv="<div class='element' id='layer'>" +
			"new layer div" +
"<button onclick='duplicate( $(this).parent() )'>new layer</button>" +
"<button onclick='deleteElement( $(this).parent() )'>delete layer</button><br /></div>";
	return layerDiv;
}

function newSoil()
{
	var soilDiv="<div class='element' id='soil'>" +
			" new soil div" +
"<button onclick='deleteElement( $(this).parent() )'>delete soil</button>" +
"<button onclick='duplicate( $(this).parent() )'>new soil</button><br />" +
"<div class='element' id='layer'>" +
			"  new layer div " +
"<button onclick='deleteElement( $(this).parent() )'>delete layer</button>" +
"<button onclick='duplicate( $(this).parent() )'>new layer</button></div></div>";
	return soilDiv;
}
