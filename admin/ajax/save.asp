<%@Language="VBScript" CodePage = 65001 %>
<% 
  Response.CharSet = "UTF-8"
  Response.CodePage = 65001
%>
<!--#include virtual="/security.asp" -->
<!--#include virtual="/functions.asp" -->
<!--#include virtual="/connection.asp" -->
<%
'get URL and get Recipe food item values
if request("t")="people" then
	sSQL=""
	sLocation=""
	if CheckExistsFile("C:\inetpub\wwwroot\antidote\people\images\"&request("uid")&".jpg") then
		sLocation="/people/images/"&request("uid")&".jpg"
	end if
	sSQL=""
	sSQL="UPDATE `antidote`.`people` SET image_path='"&sLocation&"',email ='"&request("email")&"',password = '"&request("password")&"',name = '"&request("name")&"',about_me = '"&replace(request("about_me"),"'","''")&"' WHERE uid_people = '"&request("uid")&"';"
	'x=rwb(sSQL)
	x=openRS(sSQL)
	x=closeRS()
	x=rwb("Updated!") 
end if
if request("t")="recipe" then
	sLocation=""
	'cehck to see if there is an image at C:\inetpub\wwwroot\antidote\images\recipes'
	if CheckExistsFile("C:\inetpub\wwwroot\antidote\images\recipes\"&request("uid")&".jpg") then
		sLocation="/images/recipes/"&request("uid")&".jpg"
	end if
	bAuth="0"
	bWeb="0"
	if request("show_on_web")="on" then bWeb="1"
	if request("authorized")="on" then bAuth="1"
	sSQL=""
	sSQL="UPDATE `antidote`.`recipes` SET name ='"&replace(request("name"),"'","''")&"',id_type = '"&request("type")&"',how_to_make = '"&replace(request("makeit"),"'","''")&"',show_on_web="&bWeb&",authorized="&bAuth&",temp=0,servings='"&request("servings")&"',brief='"&left(replace(request("brief"),"'","''"),200)&"'  WHERE uid_recipe = '"&request("uid")&"';"
	'ax=rwb(sSQL)
	x=openRS(sSQL)
	x=closeRS()
	x=rwb("Updated!")
end if
if request("t")="food" then
	sLocation=""
	'cehck to see if there is an image at C:\inetpub\wwwroot\antidote\images\recipes'
	if CheckExistsFile("C:\inetpub\wwwroot\antidote\images\food\"&request("name")&".jpg") then
		sLocation="/images/recipes/"&request("uid")&".jpg"
	end if
	bAuth="0"
	bWeb="1"
	wh_id=0
	'grams_default=request("food_amount")
	'x=rwe(DecodeUTF8(request("Intro")))
	if not request("wh_id")="" and isnumeric(request("wh_id")) then wh_id=request("wh_id")
	if request("show_on_web")="on" then bWeb="1"
	if request("authorized")="on" then bAuth="1"
	sSQL=""
	sSQL="UPDATE `antidote`.`food` SET name ='"&replace(request("name"),"'","''")&"',Intro = '"&replace(request("Intro"),"'","''")&"',Description='"&replace(request("Description"),"'","''")&"',wh_id="&wh_id&",visible="&bWeb&",default_unit='',grams_default=grams_default,id_person_add="&session("id_people")&",cost='"&replace(request("cost"),"'","''")&"',link='"&replace(request("link"),"'","''")&"',id_supplier='"&request("supplier")&"' WHERE uid_food = '"&request("uid")&"';"
	'x=rwe(sSQL)
	x=openRS(sSQL)
	x=closeRS()
	x=rwb("Updated!")
end if

Public Function DecodeUTF8(s)
  Set stmANSI = Server.CreateObject("ADODB.Stream")
  s = s & ""
  On Error Resume Next

  With stmANSI
    .Open
    .Position = 0
    .CharSet = "Windows-1252"
    .WriteText s
    .Position = 0
    .CharSet = "UTF-8"
  End With

  DecodeUTF8 = stmANSI.ReadText
  stmANSI.Close

  If Err.number <> 0 Then
    lib.logger.error "str.DecodeUTF8( " & s & " ): " & Err.Description
    DecodeUTF8 = s
  End If
  On error Goto 0
End Function
%>