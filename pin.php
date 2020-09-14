
<link href='https://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<style>
 body {
  font-family: 'Roboto', sans-serif;
  background-color: #f5f5f5;
}
  section {
  max-width: 700px;
  padding: 20px;
  margin: 10px auto;
  background-color: #fff;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
}
section p {
  color: #27221c;
  margin: 20px 0;
  font-size: 13sp;
}
section button {
  display: block;
  margin: 30px 0 10px 0;
  width: 100%;
  background: #2893da;
  border: none;
  color: #fff;
  padding: 15px 0;
}
section button:hover {
  background: #227bb6;
}
</style>
<section>
  <p>Pin : </p>
  <h2 id="user"></h2>
  <p>Key :</p>
  <h2 id="pass">
  </h2>
  <button id="generate">Generate Key</button>
</section>
<script>
  $(document).ready(function(){
    $.ajax({
      url:"action.php?aksi=getkey",
      success:function(result){
        result = JSON.parse(result);
        $("#user").html(result.user);
      }})
  });
  $("#generate").click(function(){
    $.ajax({
      url:"action.php?aksi=generate&pin="+$("#user").text().trim(),
      success:function(result){
        result = JSON.parse(result);
        $("#pass").html(result.pass);
      }})
  })

</script>