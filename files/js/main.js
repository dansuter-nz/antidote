function editText(idComment)
{
	//create textarea from idComment
  var htmlStr = $("#c"+idComment).html();
  //alert(htmlStr);
  CKEDITOR.instances.t.setData(htmlStr);
  $("#e").val(idComment);
  //alert ($("#e").val())
  //return false;
  
}
function vote(id_comment,iVote)
{
	//var upvotes=document.getElementById("vuc"+id_comment).innerText;
	//upvotes=parseInt(upvotes)+1;
	//jquery send vote to server
	$.post( "vote.php?c="+id_comment+"&v="+iVote, function( data ) {
		alert(data);	});
}
function down_hover()
{}