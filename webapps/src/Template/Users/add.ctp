<div class="row">
    <div class="col-md-12 center login-header">
        <h2>システム利用者登録</h2>
    </div>
</div>

<div class="row">
    <div class="well col-md-6 center box-content">
        <div class="alert alert-info">
            必要項目を入力してください
        </div>
        <?= $this->Flash->render() ?>
        <form action="/users/add" method="post">
            <fieldset>
                <div class="input-group-lg form-group col-md-12">
                    <label for="email">メールアドレス</label>
                    <input id="email" name="email" type="text" class="form-control" placeholder="メールアドレス">
                </div>
                <div class="clearfix"></div>

                <div class="input-group-lg form-group col-md-12">
                    <label for="password">パスワード</label>
                    <input id="password" name="password" type="text" class="form-control" placeholder="パスワード">
                </div>
                <div class="clearfix"></div>

                <div class="input-group-lg form-group col-md-6">
                    <label for="last_name">苗字</label>
                    <input id="last_name" name="last_name" type="text" class="form-control" placeholder="苗字">
                </div>

                <div class="input-group-lg form-group col-md-6">
                    <label for="first_name">名前</label>
                    <input id="first_name" name="first_name" type="text" class="form-control" placeholder="名前">
                </div>

                <input id="is_deleted" name="is_deleted" type="hidden" value="0">

                TODO: 店舗紐付け

                <div class="clearfix"></div>
                <p class="center col-md-5">
                    <button type="submit" class="btn btn-primary">登録</button>
                </p>
            </fieldset>
        </form>
    </div>
</div>