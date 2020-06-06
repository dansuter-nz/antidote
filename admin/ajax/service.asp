<!--#include virtual="/security.asp" -->
<!--#include virtual="/functions.asp" -->
<!--#include virtual="/connection.asp" -->
<%
'get URL and get Recipe food item values
if request("s")="1" then
	sSQL=""
	sLocation=""
	sSQL="UPDATE `antidote`.`people_meals_recipes` SET served=1,date_served =now() WHERE id_people_meals_recipes = "&request("m")&";"
	'x=rwb(sSQL)
	x=openRS(sSQL)
	x=closeRS()
	x=rwb("Updated!") 

end if
if request("s")="0" then
	sSQL=""
	sLocation=""
	sSQL="UPDATE `antidote`.`people_meals_recipes` SET served=0,date_served =null WHERE id_people_meals_recipes = "&request("m")&";"
	'x=rwb(sSQL)
	x=openRS(sSQL)
	x=closeRS()
	x=rwb("Updated!") 
end if

%>