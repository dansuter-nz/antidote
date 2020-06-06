<!--#include virtual="/security.asp" -->
<!--#include virtual="/functions.asp" -->
<!--#include virtual="/connection.asp" -->
<%
'x=rwe(request("v"))
if (request("v")="0" or request("v")="1") and isnumeric(request("r")) then
	sSQL=""
	sSQL="Update `antidote`.`recipes` set show_on_web="&request("v")&" where id_recipe="&request("r")&";"
	'x=rwe(sSQL)
	x=openRS(sSQL)
	x=closeRS()
	x=rwb("Updated")
end if
%>