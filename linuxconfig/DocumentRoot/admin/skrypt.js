function Show()
{
var oTable = document.getElementById('polao');
alert(oTable.innerHTML);


}



function Check()
{
var oForm = document.forms["addpyt"];
var oCheck = oForm.elements["pliczek"];
var oFile = oForm.elements["plik"];

		if (oCheck.checked == true)
		{
		oFile.disabled = false;
		}
		else
		{
		oFile.disabled = true;
		}
}

function Dodaj()
{
var iIle = document.getElementById('polao').rows.length;
var I = iIle + 1;
var oTable = document.getElementById('polao').tBodies[0];
var oTr = document.createElement('tr');
var oTd_a = document.createElement('td');
var oTd_b = document.createElement('td');
var oInput_a = document.createElement('input');
	oInput_a.setAttribute('type', 'checkbox');
	oInput_a.setAttribute('name', 'p[]');
	oInput_a.setAttribute('value', I);
var oInput_b = document.createElement('input');
	oInput_b.setAttribute('type', 'text');
	oInput_b.setAttribute('name', 'pp'+I);
	oInput_b.setAttribute('maxlength', '200');
	oInput_b.setAttribute('size', '50');
			
oTd_a.appendChild(oInput_a);
oTd_b.appendChild(oInput_b);
oTr.appendChild(oTd_a);
oTr.appendChild(oTd_b);
oTable.appendChild(oTr);

var iEndly = document.getElementById('polao').rows.length;
var oEnd = document.getElementById('end');
	oEnd.setAttribute('value', iEndly);
	
}


function Usun()
{
var oTable = document.getElementById('polao');
var iIle = document.getElementById('polao').rows.length;
var iUsuw = iIle - 1;
oTable.deleteRow(iUsuw);


var iEndly = document.getElementById('polao').rows.length;
var oEnd = document.getElementById('end');
	oEnd.setAttribute('value', iEndly);
}