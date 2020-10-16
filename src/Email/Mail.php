<?php
/**
 * Created by PhpStorm.
 * User: tossboy
 * Date: 2018/7/6
 * Time: 14:04
 * GMAIL
 * 默认情况下，自己编写的发送gmail邮件程序因无法访问google帐号，导致不能成功发送邮件，解决办法：开启不安全应用访问权限。
 * 登入该网站https://www.google.com/settings/security/lesssecureapps ，并启用不够安全的应用访问权限，设备就可以正常发送邮件了。
 * 如账户事先开启了两步验证，则需要先关闭两步验证功能，才能开启上述功能。
 * 其他方法参看此链接http://www.ghacks.net/2014/07/21/gmail-starts-block-less-secure-apps-enable-access/
 */

namespace Potato\Email;

use PHPMailer;
class Mail
{
    /**
     * 各邮箱smtp服务器 以及支持的协议
     * @var array
     */
    private $host = [
        'qq' => 'smtp.qq.com',// SSL/TLS/ STARTTLS（TLS）
        'gmail' => 'smtp.gmail.com',// TLS/ STARTTLS（TLS）
        'foxmail' => 'smtp.exmail.qq.com',// SSL/TLS/ STARTTLS（TLS）
        'outlook' => 'smtp-mail.outlook.com',// STARTTLS（TLS）
        'yahoo' => 'smtp.mail.yahoo.com', // TLS/STARTTLS（TLS）
        '163' => 'smtp.163.com', // SSL/TLS
        'hotmail' => 'smtp.live.com', // STARTTLS（TLS）
        'icloud' => 'smtp.mail.me.com', // STARTTLS（TLS）
        'yandex' => 'smtp.yandex.ru', // SSL/TLS/STARTTLS（SSL/TLS）
        'gmx' => 'smtp.gmx.com', // TLS/STARTTLS
        'sina' => 'smtp.sina.com', // SSL/TLS/STARTTLS（SSL/TLS）
        'aol' => 'smtp.aol.com', // TLS／STARTTLS
        'rediff' => 'smtp.rediffmail.com', // SSL/TLS/STARTTLS（SSL/TLS）
    ];
    /**
     * 对于ssl/tls加密，使用465端口
     * 对于starttls 一般使用587端口
     * @var array
     */
    private $port = [
        'ssl' => 465,
        'tls' => 465,
        'starttls' => 587,
    ];
    private $config = [
        'secure' => 'ssl',//发送的协议方式 默认ssl
        'host' => '163',//发送的邮箱名
        'email' => '',//发送方邮箱
        'password' => '',//邮箱密码
    ];

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config,$config);
    }

    /**
     * 发送邮件
     * @param $tomail  接收方邮箱
     * @param $title  邮件标题
     * @param $content 邮件内容
     * @param array $attachment 邮件附件
     * @return bool|string 成功则返回true 失败则返回错误信息
     * @throws \phpmailerException
     */
    public function sendMail($tomail,$title,$content,$attachment=[]){
        $mail = new PHPMailer();
        $mail->isSMTP();// 使用SMTP服务
        $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = $this->host[$this->config['host']];// 发送方的SMTP服务器地址
        $mail->SMTPAuth = true;// 是否使用身份验证
        $mail->Username = $this->config['email'];// 发送方的邮箱用户名
        $mail->Password = $this->config['password'];// 发送方的邮箱密码，注意用163邮箱和QQ这里填写的是“客户端授权密码”而不是邮箱的登录密码！
        $mail->SMTPSecure = $this->config['secure'];// 使用的协议方式
        $mail->Port = $this->port[$this->config['secure']];// 协议方式端口号
        $mail->From= $this->config['email'];
        $mail->setFrom($this->config['email'],'');// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示
        $mail->addAddress($tomail,'');// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)
        $mail->IsHTML(true);
        $mail->Subject = $title;// 邮件标题
        $mail->Body = $content;// 邮件正文
        if (is_array($attachment)) { // 添加附件
            foreach ($attachment as $file) {
                is_file($file) && $mail->AddAttachment($file);
            }
        }
        return $mail->Send() ? true : $mail->ErrorInfo;//发送邮件
    }
}