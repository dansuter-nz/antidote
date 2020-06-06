<!-- #include virtual="/connection.asp"-->
<!--#include virtual="/functions.asp" -->
<!--#include virtual="/header.asp" -->
<div class="row row-centered">
	<div class="col-xs-12">
		<span class="">
<%
if len(request("p"))>0 and isnumeric(request("p")) then
	sSQL = "SELECT id_project,id_person,title,intro,description,created,pn.name FROM antidote.projects p inner join people pn on p.id_person=pn.id_people where id_project="&request("p")&""
	'rwb(sSQL)
	x=openRS(sSQL)
	irow=0
	if not rsTemp.eof then
		if irow mod 2=1 then strClass="light_blue_row" else strClass="white_row"
		irow=irow+1
		h="<h3>"&rstemp("title")&"</h3>"
		h=h&"<p>"&rstemp("name")&"</p>"
		h=h&"<p>on "&rstemp("created")&"</p>"
		h=h&"<p>updated "&rstemp("created")&"</p>"
		h=h&"<p>"&rstemp("intro")&"</p>"			
		h=h&"<p>"&rstemp("description")&"</p>	"
	end if
	rw(h)
else
	sSQL = "SELECT id_project,id_person,title,intro,description,created,pn.name,url_link FROM antidote.projects p inner join people pn on p.id_person=pn.id_people"
	'rwb(sSQL)
	x=openRS(sSQL)
	h=""
	irow=0
	do until rsTemp.eof 
		if irow mod 2=1 then strClass="light_blue_row" else strClass="white_row"
		irow=irow+1
		h=h&"<h3>"&rstemp("title")&"</h3>"
		h=h&"<p>"&rstemp("name")&"</p>"
		h=h&"<p>on "&rstemp("created")&"</p>"
		h=h&"<p>updated "&rstemp("created")&"</p>"
		h=h&"<p><b>Link</b> <a href="""&rstemp("url_Link")&""">"&rstemp("url_Link")&"</a></p>"
		h=h&"<p><b>"&rstemp("intro")&"</b></p>"			
		h=h&"<p>"&rstemp("description")&"</p>	"
		rsTemp.movenext
	loop
	rw(h)
end if
%>		
		
		</span>
	</div>
</div>

<hr>
<div class="row row-centered">
	<div class="col-md-12 text-center" >
		<img src="/images/socrates_change.jpg">
	</div>
</div>		
</br>
<!--#include virtual="/footer.asp" -->