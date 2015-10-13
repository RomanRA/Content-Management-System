<?php
$title='Request Password';
include_once 'header.php';
include_once 'menu.php';
?>
<body id="colorbody">
   <div id="main">
       <div class ="container">
           <div class = "row">
               <div class = "col-lg-9">
                   <div class ="panel panel-default">
                       <div class ="panel-body">
                           <div class= "page-header">
                               <h3><b>Request Password Reset</b></h3>

                        <form class=form-horizontal" role="form"  autocomplete="on" name="passrequest" id="passrequest" method ="post" action="/service/rpreq">

                            <div class="form-group">
                                <label for="username" class ="col-lg-2 control-label">* User Name</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="username" id="username" size="30" maxlength="10" value="" autofocus="autofocus" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="group" class="col-lg-2 control-label">* Group</label>
                                <div class="col-sm-4">
                                    <select  class="form-control" name="group" id="group" required="">
                                        <option value=""<?php if(isset($FORMFIELD['group'])){echo $FORMFIELD['group'];}?>>Select group</option>
                                        <option value="scholars">Scholars Academy Student</option>
                                        <option value="ugrads">Undergraduate</option>
                                        <option value="grads">Graduate</option>
                                        <option value="staff">University Staff</option>
                                        <option value="faculty">University Faculty</option>
                                        <option value="external">Guest User&nbsp(External)</option>
                                    </select>
                                </div>
                            </div>



                            <tr><td><strong>Instructions:</strong> If you have forgotten your Account password, complete the form above. A confirmation e-mail will be sent
                             to you with further instructions. check your "spam" folder if you do not receive the e-mail.</td></tr>

                            <tr><td>Note that this reset form is ONLY for your CI account. If you have forgotten your main University password, use the
                             <a href="https://www.coastal.edu/search/password/index.html?">ITS password lookup tool</a></tr></td>
                            <div class = "modal-footer">
                                <button class = "btn btn-default" type="reset">Clear</button>
                                <button class ="btn btn-inverse" name="submit" id="submit" type="submit"> Submit and login</button>
                            </div>
                        </form>
                   <p>* Indicates a required field.</p>
               </div>
</body>
<?php

include_once 'footer.php';

?>