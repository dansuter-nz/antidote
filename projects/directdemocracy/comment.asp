<%session("redirect_to")="/projects/directdemocracy/comment.asp?c="&request("c")%>
<!-- #include virtual="/security.asp"-->
<!-- #include virtual="/connection.asp"-->
<!--#include virtual="/functions.asp" -->
<!--#include virtual="/header.asp" -->
<!--#include virtual="/projects/position.asp" -->
<div class="row row-centered">
	<div class="col-xs-12">
		<span class="">
<%
alert=""
if request("t")<>"" then
	'submitting a comment
	y=2018
	t=replace(request("t"),"'","''")
	t=replace(t,chr(13),"</br>")
	p=session("id_people")
	c=request("c")
	e=request("e")
	sSQl="CALL adds.P_Ins_Comment ('"&t&"',"&c&","&p&","&y&","&e&")"
	'x=rw(sSQL)
	openRS(sSQL)
	alert="<tr><td>!"&rsTemp(0)&"!</td></tr>"
	closeRS()
	
end if

sYear=2018
irow=0
sTitle="Spending by Department "&sYear
sOby="department asc"
if request("c")<>"" then
	sOby=" sum(amount) desc"
	sSQL = "CALL adds.getCategorySpendingbyID ('"&request("c")&"',"&sYear&")"
	'rwb(sSQL)
	x=openRS(sSQL)
	if not rsTemp.eof then
		h="<form name=""comments"" action=""comment.asp"" method=""post"">"
		h=h&"<input type=""hidden"" value="""&request("c")&""" id=""c"" name=""c"">"
		h=h&"<input type=""hidden"" value=""0"" id=""e"" name=""e"">"
		h=h&"<h1>Our spending by '"&rsTemp("CategoryName")&"' for "&sYear&"</h1>"
		h=h&"<table><tr class=""trHead""><td>Category and "
		h=h&" Current Scope</td>"
		h=h&"<td>Details of this category</td></tr>"
		irow=0
		do until rsTemp.eof 
			if irow mod 2=1 then strClass="light_blue_row" else strClass="white_row"
			irow=irow+1
			sCat=""
			if not isnull(rstemp("categoryName")) then sCat=rstemp("categoryName")
			h=h&"<tr><td style=""width:50%;vertical-align:text-top;""><div style=""text-align:top;""><a href=""now.asp?c="&request("c")&""">"&sCat&"</a>"
			h=h&""&rstemp("Current_Scope")&"</div></td>"
			h=h&"<td style=""width:50%;vertical-align:text-top;""><b>Classification: </b>"&rstemp("Functional_Classification")&"</br>"
			h=h&"<b>Amount Type:</b> "&rstemp("amount_type")&"</br>"
			h=h&"<b>Restrictions: </b>"&rstemp("Restriction_Type")&"</br>"
			h=h&"<b>Group Type: </b>"&rstemp("group_type")&"</br>"
			h=h&"<b>Reason Name: </b>"&rstemp("AppropriationName")&"</br>"
			h=h&"<b>Reason Category: </b>"&rstemp("Appropriation_or_Category_Type")&"</br>"
			h=h&"<b>Owned By: </b>"&rstemp("Portfolio_Name")&"</br>"
			h=h&"<span class=""blackBold"">Total Budget $"&formatNumber(rstemp("total_budget"),0)&",000</span></td></tr>"
			
			if irow=1 then
			c="<tr><td colspan=""2"" style=""width:50%;vertical-align:text-top;"">"
			c=c&"<div style=""text-align:top;margin-top:20px;margin-bottom:20px;"">This is your chance to have your say on "&sCat&"'s spending of <span class=""blackBold"">"
			c=c&"$"&formatNumber(rstemp("total_budget"),0)&",000</a>.</span><div style=""text-align:bottom;margin-top:20px;margin-bottom:20px;"">Comments <b>"&rstemp("comments")&"</b> Votes <b>"&rstemp("votes")&"</b></div></td></tr> "
			end if
			rsTemp.movenext
		loop
	end if
	h=h&c
	h=h&alert
	h=h&"<tr><td colspan=""2"">"
	if session("image_path")="" then
		sImage="<a href=""http://www.antidote.org.nz/login.asp"">Login</a>"
	else
		sImage="<img src="""&replace(session("image_path"),"/med/","/thumb/")&""">"
	end if
	h=h&"<div class=""row row-centered""><div class=""col-md-2 text-center"">"&sImage&"</div>"
	h=h&"<div class=""col-md-8 text-center""><textarea id=""t"" name=""t"" placeholder=""Make your comment here."" style=""height: 150px;width:100%;""></textarea></div>"
	h=h&"<div class=""col-md-2"" id=""dvSubmit"" style=""""><div class=""row row-centered""><input name=""submit"" id=""submit"" value=""Comment"" type=""submit"" style=""""></div></div>"
	h=h&"</div>"
	h=h&"</td></tr>"
	sSQL = "CALL adds.getCommentsByCategoryID ('"&request("c")&"')"
	'rwb(sSQL)
	x=openRS(sSQL)
	if not rsTemp.eof then
		do until rsTemp.eof 
			h=h&"<tr><td colspan=""2""><div class=""row row-centered"">"
			h=h&"<div class=""col-md-2 text-center"" ><img src="""&replace(rstemp("image_path"),"/med/","/thumb/")&"""></div>"
			dAdd=rstemp("date_added")
			if left(dAdd,6)="0 days" then 
				dAdd=right(dAdd,len(dAdd)-7)
			else
				dAdd=left(dAdd,6)
			end if
			if left(dAdd,7)="0 hours" then 
				dAdd=right(dAdd,len(dAdd)-8)
			else
				dAdd=left(dAdd,7)
			end if
			if left(dAdd,6)="0 hours" then dAdd=right(dAdd,len(dAdd)-7)	
			h=h&"<div class=""col-md-8"">"
			h=h&"<div class=""row row-centered"">"
			h=h&"	<div class=""col-md-12 commentedBy"" >"&rstemp("name")&" " & dAdd &" ago</div> "
			h=h&"	<div id=""c"&rstemp("id_comment")&""" class=""col-md-12 bgCommentText"">"&rstemp("text")&"</div>"
			if session("id_people")=rstemp("id_people") then
				h=h&"	<div class=""col-md-12""><a href=""#"" onclick=""return editText("&rstemp("id_comment")&")"">Edit</a></div>"
			end if
			h=h&"</div>"
			h=h&"</div>"
			h=h&"<div class=""col-md-2 text-center nowrap"" >"
			h=h&"<div class=""vote"">"
			h=h&"<a class=""vote-up-off"" title=""This question shows research effort; it is useful and clear"" onclick=""vote("&rstemp("id_comment")&",1)"">up vote</a>"
			if isnull(rstemp("Votes")) then
				iVote=0
			else
				iVote=rstemp("Votes")
			end if
			h=h&"<span itemprop=""upvoteCount"" class=""vote-count-post"">"&iVote&"</span>"
			h=h&"<a class=""vote-down-off"" title=""This question does not show any research effort; it is unclear or not useful"" onclick=""vote("&rstemp("id_comment")&",-1)"">down vote</a>"
			h=h&"</div>"
			h=h&"</div></td></tr>"
			rsTemp.movenext
		loop
	end if
	'rw(h)
	h=h&"</form>"
	h=h&"</table>"
	rw(h)
	'rwb(sSQL)
end if
%>		

	
		</span>
	</div>
</div>
 <script>
  CKEDITOR.replace('t');
</script>
<hr>
<div class="row row-centered">
	<div class="col-md-12 text-center" >
		<img src="/images/socrates_change.jpg">
	</div>
</div>		
</br>
<!--#include virtual="/footer.asp" -->