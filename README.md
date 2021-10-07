# 旋转验证码 - PHP接口v0.1
![](https://s3.bmp.ovh/imgs/2021/10/5782d8b3cbde6179.png)
### 在线示例：[点击查看](http://rotatecaptcha.demo.api0.cn/)
****
### 国内访问加速：
Gitee码云：[点击跳转](https://gitee.com/t1zf/rotateCaptcha/)
**** 
### 旋转验证码优点
- 兼容pc和手机操作，拖动滑块即可完成验证，不像传统验证码需要输入数字或字母
- 验证码底图可以无限多，录入底图仅需保证图片为正角即可，且每张底图生成后有随机干扰噪点，无法暴力缓存底图通过对比破解
- 滑块拖动时会保存轨迹信息，可以分析轨迹数据识别真人或人机
### 缺点
- 目前前端依赖组件库太多，接入其他项目较繁琐(已加入TODO优化待办事项)
- 无法对抗打码平台(对人机效验要求高的话建议加入结合上下文的二次效验，例如旋转验证码通过后再弹出输入框让用户数据自己账号的结尾两位字符)
****
### 运行环境要求:
- web服务器：Nginx或Apache
- php脚本环境：PHP8(需要安装扩展：redis，取消禁用函数: shell_exec)
- 数据库：MySQL 5.6
- 缓存及Session：Redis
- 验证码生成：NodeJs
- (建议使用宝塔搭建运行环境)
### 安装教程：
1. 克隆项目到本地;
2. 使用宝塔创建网站，设置伪静态:
- Apache
```
RewriteEngine on
RewriteRule ^(.*?).json$ $1.php
RewriteRule ^nodejs /404.html
```
- Nginx
```
rewrite ^(.*).json$ /$1.php;
rewrite ^\/nodejs /404.html;
```
3. 创建数据库,数据库名称以你项目为准
4. 导入`./rotateCaptcha.sql`文件到数据库
5. 修改配置文件`./lib/config.php`，注意：配置node路径时默认填写`node`即可，若php无nodejs执行权限，则需要填写nodejs的安装路径，并修改nodejs安装目录权限为`755`用户组`www`
6. 配置nodejs验证码生成模块，命令行进入web目录`./nodejs/captcha/`执行安装扩展命令：
```
npm i
```
7. 通过浏览器访问刚配置好的网站，如果看到验证码测试页面，即代表安装成功！
****
### vue前端接入：
- VueCli示例源码：[查看开源地址](https://github.com/1615958039/rotateCaptcha_vuecli)
- UNIAPP示例源码：[查看开源地址](https://github.com/1615958039/rotateCaptcha_uniapp)
****
文档还在继续完善中，敬请期待...
****
## 开源不易，点个Star支持一下呗！
****
如安装过程或对源码有疑义的地方可提issue或联系作者私人QQ `1615958039`


