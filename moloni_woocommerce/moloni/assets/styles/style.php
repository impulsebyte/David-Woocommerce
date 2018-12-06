<style>
	#content{
		width: 100%;
		margin-top: 60px;
	}
	#contentTable{
		width: 100%;
	}
	
.header{
	margin-top: 25px;
}
	
.icon-48-moloni-titulo{
	background: url("../images/48logo.png") 0 0 no-repeat !important;
}

hr{
	border: 0;
    height: 1px;
    background: #333;
    background-image: -webkit-linear-gradient(left, #ccc, #333, #ccc); 
    background-image:    -moz-linear-gradient(left, #ccc, #333, #ccc); 
    background-image:     -ms-linear-gradient(left, #ccc, #333, #ccc); 
    background-image:      -o-linear-gradient(left, #ccc, #333, #ccc); 
}

.tituloTab{
	padding: 6px 0 3px 0;
	font-size: 18px;
	line-height: 120%;
	margin-left: 10px;
	color: #000;
	border-bottom: 1px solid #ddd;
}


.tableOptions{
	width: 100%;
	font-size:14px;
}
.tableOptions td:nth-child(1){
	text-align:right;
}
.tableOptions td:nth-child(2){
    padding-left: 15px;
}


.listform{
	list-style-type: none;
	margin: 0px;
	padding: 0px;
}
.listform li{
	border-bottom: 1px solid #ddd;
	min-height: 30px;
	line-height: 115%;
	padding: 4px 1px;
}
.listform label{
	display: inline-block;
	width: 160px;
	text-align: right;
	font-weight: bold;
	font-family: sans-serif;
	margin-right: 8px;
	margin-top: 8px;
	font-size: 12px;
}


.actionButton{
	height: 30px;
	line-height: 30px;
	padding: 0 9px;
	display: inline-block;
	color: #000 !important;
	text-decoration: none !important;
	border-right: 1px solid #fff;
	border-top: 1px solid #fff;
	margin-right: 1px;
	cursor: pointer;
	font-weight: bold;
	text-shadow: 2px 2px 1px #fff;
	-moz-box-shadow: 0px 0px 6px 0px #6FB8DB;
	-webkit-box-shadow: 0px 0px 6px 0px #6FB8DB;
	box-shadow: 0px 0px 6px 0px #6FB8DB;
	-moz-border-radius: 3px 3px 0 0;
	-webkit-border-radius: 3px 3px 0 0;
	border-radius: 3px 3px 3px 3px;
	background: #fff;
	background: -moz-linear-gradient(top, #ffffff, #dddddd);
	background: -o-linear-gradient(top, #fff, #ddd);
	background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#dddddd));
	background-image: linear-gradient(to bottom, #fff 0%, #ddd 100%);
	float: right;
}

.actionButton:hover{
	background:-moz-linear-gradient(top, #ffffff, #CCFF00);
	background:-o-linear-gradient(top, #fff, #CCFF00);
	background:-webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#CCFF00));
	background-image:linear-gradient(to bottom,  #fff 0%, #cf0 100%);
	background-image:ms-linear-gradient(top, #fff 0%, #cf0 100%)
}

.inputOut{
	width: 330px;
	border: 1px solid #CCC !important;
	color: #333 !important;
	font-size: 14px !important;
	background-color: #FFF !important;
	padding: 6px 4px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
}


.msgAlertaForms{
	border: 1px dashed #ddd;
	background-color: #feff71;
	margin-top: -30px;
	text-align: center;
	width: 200px;
	margin-left: 77px;
	height: 24px;
	vertical-align: middle;
	padding-top: 4px;
}

/* ============= Login ==============*/
#formLogin{ 	
	margin-left: auto;	
	margin-right: auto;		
	width: 574px;	
	margin: 15px auto 15px auto;	
	background-color: #fff;	
	padding: 15px;	
	-moz-border-radius: 6px;	
	-webkit-border-radius: 6px;	
	border-radius: 6px;	
	-moz-box-shadow: 0px 0px 15px 2px #ccc;	
	-webkit-box-shadow: 0px 0px 15px 2px #ccc;	
	box-shadow: 0px 0px 15px 2px #ccc;
	margin-top: 200px;
}
#formLogin a, a:visited {		
	text-decoration: none;	
}
#formLogin hr{	
	opacity: 0.6;	
	z-index: 100;	
}
#formPerm{	
	margin-left: auto;	
	margin-right: auto;	
	width: 485px;
}
#formLogin input[type=text], input[type=password] {	
	width: 380px;	
	border: 1px solid #CCC !important;	
	color: #333 !important;	
	font-size: 14px;	
	background-color: rgb(250, 255, 189);	
	padding: 6px 4px;	
	-moz-border-radius: 3px;	
	-webkit-border-radius: 3px;	
	border-radius: 3px;
}
#formLogin td:nth-child(1) {	
	width: 100px;	
	text-align: right;	
	font-weight: bold;	
	font-family: sans-serif;	
	margin-right: 8px;	
	margin-top: 8px;	
	padding-right: 8px;
}
#formLogin input[type=submit], input[type=reset] {	
	border: 1px solid #ccc;	
	color: #000;	
	text-shadow: 2px 2px 1px #fff;	
	padding: 6px 8px;	
	cursor: pointer;	
	-moz-border-radius: 3px;	
	-webkit-border-radius: 3px;	
	border-radius: 3px;		
	background: #fff;	
	background: -moz-linear-gradient(top, #ffffff, #eaeaea);	
	background: -o-linear-gradient(top, #fff, #eaeaea);	
	background: -webkit-gradient(linear, left top, left bottom,from(#ffffff),to(#eaeaea));	
	filter: progid:DXImageTransform.Microsoft.Gradient(StartColorStr='#ffffff',EndColorStr='#eaeaea',GradientType=0)
}
.power{	
	float:right;	
	font-size: 9px;	
	height:20px;	
	z-index: 99;
}

/* ==================================*//* ============ SELECT EMPRESAS ============*/
.outBoxEmpresa{	
	margin-left: auto;	
	margin-right: auto;		
	width: 990px;
	margin-top: 200px;
}
.caixaLoginEmpresa {	
	float: left;	
	margin: 10px 15px 20px 0;	
	background-color: #fff;	
	padding: 15px;	
	width: 200px;	
	cursor: pointer;	
	-moz-border-radius: 6px;	
	-webkit-border-radius: 6px;	
	border-radius: 6px;	
	-moz-box-shadow: 0px 0px 15px 2px #ccc;	
	-webkit-box-shadow: 0px 0px 15px 2px #ccc;	
	box-shadow: 0px 0px 15px 2px #ccc;	
	min-height: 250px;
}
/* ============ SELECT EMPRESAS ============*/



/*=============== TABELAS ====================*/
.listHolder{
	padding: 6px;
	min-height: 150px;
	overflow: hidden;
	border: 1px solid #fff;
	background-color: #fff;
	position: relative;
	-moz-box-shadow: 0px 0px 6px 1px #6FB8DB;
	-webkit-box-shadow: 0px 0px 6px 1px #6FB8DB;
	box-shadow: 0px 0px 6px 1px rgb(46, 141, 187);
	border-radius: 2px;
	margin-bottom: 10px;
}
.tblMoloni {
	width: 99%;
	border-collapse: collapse;
}
.tblMoloni thead th{
	text-align: center;
	padding: 7px 4px;
	font-weight: bold;
	color: #fff;
	text-shadow: 1px 1px 3px #555;
	vertical-align: middle;
	background-color: #2C94AD;
	background: -moz-linear-gradient(top, #70D3E9, #2C94AD);
	background: -webkit-gradient(linear, left top, left bottom, from(#70D3E9), to(#2C94AD));
	background: -o-linear-gradient(top, #70D3E9, #2C94AD);
	filter: progid:DXImageTransform.Microsoft.Gradient(StartColorStr='#70D3E9', EndColorStr='#2C94AD', GradientType=0);
	/* background-image: linear-gradient(bottom, rgb(15,86,134) 92%, rgb(0,148,240) 16%);
background-image: -o-linear-gradient(bottom, rgb(15,86,134) 92%, rgb(0,148,240) 16%);
background-image: -moz-linear-gradient(bottom, rgb(15,86,134) 92%, rgb(0,148,240) 16%);
background-image: -webkit-linear-gradient(bottom, rgb(15,86,134) 92%, rgb(0,148,240) 16%);
background-image: -ms-linear-gradient(bottom, rgb(15,86,134) 92%, rgb(0,148,240) 16%);

background-image: -webkit-gradient(
	linear,
	left bottom,
	left top,
	color-stop(0.92, rgb(15,86,134)),
	color-stop(0.16, rgb(0,148,240))
);*/
	margin-radius: 3px;
}
.tblMoloni tbody tr:nth-child(even) td {
	background-color: #eaeaea;
}
.tblMoloni tr:hover td{
	background-color: rgb(227, 255, 185) !important;
	cursor: pointer;
}
.tblMoloni tbody tr td{
	vertical-align: middle;
	border-bottom: 1px solid #ddd;
	padding: 5px;
	padding-left: 15px;
	
	font-family: Arial,Helvetica,Tahoma,Verdana,Arial,Helvetica,sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #333;
	line-height: 120%;
}
.tblMoloni .child{
	opacity: 0.6;
	font-size: 11px;
}
.tblMoloni tbody tr td {
	text-align: center;
}
.tblMoloni .left{
	text-align: left !important;
}
.tblMoloni .right{
	text-align: right !important;
}
.tblMoloni tbody tr td a:link{
	color: #1E90FF;
	text-decoration: none;
	text-align: center;
}
.tblMoloni tbody tr td a:visited{
	color: #1E90FF;
	text-decoration: none;
}
.tblMoloni tbody tr td a:hover{
	color: black;
	text-decoration: underline;
}
/* ================== */
#optbox{
	border: solid 1px rgb(204, 204, 204);
	margin: 10px 10px 23px 0px;
	padding: 15px;
}

</style>