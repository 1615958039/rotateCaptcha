/**
 * Node点坐标合成脚本
 * node index.js [验证码原图] [验证码输出地址] [验证码图旋转角度] [画布大小] [随机点数量] [随机线条数量] [随机大矩形数量]
 */
const { createCanvas, loadImage } = require('canvas');
let fs = require('fs');




let initImg = process.argv[2];
if (!initImg) {
    console.log("请传入需要生成验证码的原图")
    process.exit();
}
try {
    fs.accessSync(initImg, fs.constants.R_OK);
} catch (error) {
    console.log("无法访问验证码原图")
    process.exit();
}

let outImg = process.argv[3];
if (!outImg) {
    console.log("请传入验证码的输出的地址")
    process.exit();
}

let rotationAngle = Number(process.argv[4]);
if (rotationAngle < 0 || rotationAngle > 360) {
    console.log("请传入正确的验证码旋转角度")
    process.exit();
}

let canvasSize = process.argv[5] == undefined ? 480 : Number(process.argv[5]);
let randomPoint = process.argv[6] == undefined ? 200 : Number(process.argv[6]);
let randomLine = process.argv[7] == undefined ? 50 : Number(process.argv[7]);
let randomBlock = process.argv[8] == undefined ? 3 : Number(process.argv[8]);

drawCapImage(initImg, outImg, rotationAngle, canvasSize, randomPoint, randomLine, randomBlock).then(() => {

    console.log("success")

}).catch(err => {
    console.log("验证码图片生成失败")
    process.exit();
})









/**
 * 画线条
 * rotate 围绕线条中心旋转
 */
function drawLine(ctx, x = 0, y = 0, width = 10, height = 1, bgColor = "#fff", rotate = 0) {

    ctx.translate(x + width / 2, y + height / 2);
    ctx.rotate((rotate) * Math.PI / 180);

    ctx.fillStyle = bgColor;
    ctx.fillRect(width / 2 * -1, height / 2 * -1, width, height);

    ctx.rotate((360 - rotate) * Math.PI / 180);
    ctx.translate((x + width / 2) * -1, (y + height / 2) * -1);

}


/**
 * 生成从minNum到maxNum的随机数
 */
function randomNum(minNum, maxNum) {
    switch (arguments.length) {
        case 1:
            return parseInt(Math.random() * minNum + 1, 10);
            break;
        case 2:
            return parseInt(Math.random() * (maxNum - minNum + 1) + minNum, 10);
            break;
        default:
            return 0;
            break;
    }
}


/**
 * 验证码图片绘制
 */
async function drawCapImage(initImg, outImg, rotationAngle, canvasSize = 480, randomPoint = 200, randomLine = 50, randomBlock = 3) {
    return new Promise((yes, err) => {
        try {
            loadImage(initImg).then((image) => {
                const canvas = createCanvas(canvasSize, canvasSize); //创建画板
                const ctx = canvas.getContext('2d');
                let option = { //图片写入参数
                    h: canvasSize, //高度
                    w: canvasSize //宽度
                }
                if (image.height > image.width) {
                    //高大于宽，竖屏照片
                    //设置高等于300,宽自动变化
                    let c = Math.ceil(canvasSize / image.width * 100) / 100;
                    option.h = Math.ceil(c * image.height);
                    option.w = canvasSize;
                } else if (image.height < image.width) {
                    let c = Math.ceil(canvasSize / image.height * 100) / 100;
                    option.h = canvasSize;
                    option.w = Math.ceil(c * image.width);
                }
                ctx.translate((canvasSize / 2), (canvasSize / 2));
                ctx.rotate(rotationAngle * Math.PI / 180);
                ctx.arc(0, 0, (canvasSize / 2), 0, 2 * Math.PI); //限制在直径为150的圆内画图
                ctx.clip();
                ctx.translate(-(canvasSize / 2), -(canvasSize / 2));
                ctx.drawImage(image, 0, 0, option.w, option.h);
                ctx.translate((canvasSize / 2), (canvasSize / 2));
                ctx.rotate((360 - rotationAngle) * Math.PI / 180);
                ctx.translate(-(canvasSize / 2), -(canvasSize / 2));
                /**
                 * 生成随机颜色点
                 */
                for (let index = 0; index < randomPoint; index++) {
                    drawLine(ctx,
                        randomNum(0, canvasSize),
                        randomNum(0, canvasSize),
                        randomNum(0, 4),
                        randomNum(0, 4),
                        "#" + (Array(6).join(0) + randomNum(0, 999999)).slice(-6),
                        randomNum(0, 360)
                    )
                }

                /**
                 * 随机生成线条
                 */
                for (let index = 0; index < randomLine; index++) {
                    drawLine(ctx,
                        randomNum(0, canvasSize),
                        randomNum(0, canvasSize),
                        randomNum(0, 200),
                        randomNum(0, 2),
                        "#" + (Array(6).join(0) + randomNum(0, 999999)).slice(-6),
                        randomNum(0, 360)
                    )
                }

                /**
                 * 随机生成大矩形
                 */
                for (let index = 0; index < randomBlock; index++) {
                    drawLine(ctx,
                        randomNum(0, canvasSize),
                        randomNum(0, canvasSize),
                        randomNum(0, 300),
                        randomNum(0, 40),
                        "#" + (Array(6).join(0) + randomNum(0, 999999)).slice(-6),
                        randomNum(0, 360)
                    )
                }
                canvas.createPNGStream().pipe(fs.createWriteStream(outImg))
                yes()
            })
        } catch (error) {
            err(error)
        }
    })
}