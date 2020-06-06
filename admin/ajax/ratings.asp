<!--#include virtual="/security_ajax.asp" -->
<!--#include virtual="/functions.asp" -->
<!--#include virtual="/connection.asp" -->
<%
'get URL and get Recipe food item values
if request("r")="" then
    x=rwe("No rating end")

else
    'must be a rating for a recipe'
    sSQL=""


    sSQL="INSERT INTO `antidote`.`reviews`(`id_Recipe`,`id_person`,`stars`,`review_text`)"
    sSQL=sSQL&" Select  "&request("r")&","&request("p")&","&request("s")&",'"&request("t")&"';"
    'x=rwe(sSQL)
    x=openRS(sSQL)
    x=closeRS()
    'x=rwe(request("s"))
    sSQL="call get_review_last ("&request("r")&");"
    x=openRSA(sSQL)
    iStarsMax=5
    person_image=replace(rsTempA("image_path"),"/med/","/thumb/")
    s=s&"<div class=""row "">"
    s=s&"   <div class=""col-sm-3 col-xs-6"">"
    s=s&"       <div class=""recipe_choice"">"
    s=s&"           <div id=""r"&rsTempA("id_review")&""" class=""rate_widget"">"
    for i=1 to iStarsMax
        if i<=rsTempA("stars") then
            s=s&"       <div class=""star_"&i&" ratings_vote""></div>"
        else
            s=s&"       <div class=""star_"&i&" ratings_stars_empty""></div>"
        end if
    next
    s=s&"           </div>"
    s=s&"       </div>"
    s=s&"   </div>"
    s=s&"   <div class=""col-sm-9 col-xs-6""  style=""background-color: yellow;"">"
   '' s=s&"<div style=""display: table-cell; vertical-align: middle;min-height:45px;"">"
    s=s&"       <div class=""row review_divider"">"
    s=s&"           <div class=""col-sm-2 col-xs-6""><img src="""&person_image&""" alt=""name""></div>"
    s=s&"           <div class=""col-sm-2 col-xs-6"">"&CvbShortdate(rsTempA("date_reviewed"))&"</div>"
    s=s&"           <div class=""col-sm-8 col-xs-6"">"&rsTempA("review_text")&"</div>"
    's=s&"</div>"
    s=s&"       </div>"
    s=s&"   </div>"
    s=s&"</div>"
    x=rwe(s)
    'x=rwb("inserted "&sSQL) 
end if
%>