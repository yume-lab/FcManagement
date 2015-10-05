<?php $this->assign('title', 'ログイン'); ?>

<div class="row">
    <div class="col-md-12 center login-header">
        <h2>ログイン</h2>
    </div>
</div>

<div class="row">
    <div class="well col-md-5 center login-box">
        <div class="alert alert-info">
            メールアドレスとパスワードを入力してください
        </div>
        <form class="form-horizontal" action="/login/auth" method="post">
            <fieldset>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
                    <input name="email" type="text" class="form-control" placeholder="Username">
                </div>
                <div class="clearfix"></div>
                <br>

                <div class="input-group input-group-lg">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock red"></i></span>
                    <input name="password" type="password" class="form-control" placeholder="Password">
                </div>
               <div class="clearfix"></div>

                <p class="center col-md-5">
                    <button type="submit" class="btn btn-primary">ログイン</button>
                </p>

            </fieldset>
        </form>
    </div>
</div>