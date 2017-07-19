<?php
一、数据库迁移

    步骤：
    （1）创建迁移文件   （生成大体框架）
    （2）设计迁移文件   （设计表结构字段）
    （3）执行迁移         （生成表结构）
    （4）创建种子文件   （就是准备要添加的数据）
    （5）执行种子文件   （将要添加的数据添加到数据库中）

 1、建立迁移文件
    使用artisan 的make:migration命令生成数据表的迁移文件（就是数据表的结构）

    php  artisan  make:migration   create_user_table
    创建了create_user_table的数据表迁移文件，默认生成位置在 database/migrations 下

    --table和--create选项可以用于指定表名以及该迁移是否要创建一个新的数据表
    注意 --table 的表必须是存在的，
         --create的表是新建出来的

    php artisan make:migration create_student_table --table=student
    php artisan make:migration create_goods_table --create=goods


2、设计迁移文件
    根据需要设计表的字段
     public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('airline');
            $table->timestamps();
        });
    }



3、执行迁移
    要运行应用中所有未执行的迁移，可以使用 Artisan 命令提供的migrate方法：

    php artisan migrate

    php artisan migrate --force 强制迁移，不提示


回滚迁移
想要回滚最新的一次迁移”操作“，可以使用rollback命令，注意这将会回滚最后一批运行的迁移，可能包含多个迁移文件：

php artisan migrate:rollback

php artisan migrate:rollback --step=5   回滚指定数目的迁移

php artisan migrate:reset       回滚所有的应用迁移

php artisan migrate:refresh     先回滚所有数据库迁移，然后运行migrate命令
php artisan migrate:refresh --seed

php artisan migrate:refresh --step=5




4、创建种子文件
    php artisan  make:seeder    GoodsTableSeeder

    种子文件就是用来准备要添加到数据库中的数据的，可以使用DB类来进行添加，也可以创建模型，调用模型的添加方法进行添加



5、执行种子文件
      php artisan db:seed --class=UserTableSeeder



二、关系查询（就是连表查询）

   1、 一对一关系查询
    在模型中新建方法
        return $this->hasOne('App\Phone', 'foreign_key', 'local_key');


    2、一对多
        return $this->hasMany('App\Comment', 'foreign_key', 'local_key');

    3、多对多
        return $this->belongsToMany('App\Category','article_category','article_id','category_id');