## アゲラー向けの売上管理システム

### 開発環境構築

1. 仮想環境を動かすためのソフトインストール
    - [Vagrant](https://www.vagrantup.com/)
    - [Virtualbox](https://www.virtualbox.org/)

2. Gitからソースを取得
    ```
    $ git clone git@github.com:ippei-fukamatsu/AgeraManagement.git
    ```

3. vagrantの起動
    ```
    $ cd AgeraManagement/vagrant
    $ vagrant up
    ```

4. 起動確認後、ログイン
    ```
    $ vagrant ssh
    ```

5. http://192.168.33.11 にアクセス
