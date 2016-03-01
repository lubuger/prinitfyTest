<!doctype html>
<html lang="en">
    <head>
    <meta charset="UTF-8">

    <style type="text/css">
        h4 {font-family: sans-serif;}
        p {font-family: sans-serif;}
        a {font-family: sans-serif; color:#d15423; text-decoration:none;}
        #canvas {
            border: 1px solid black;
        }
    </style>
     

    <title>Printify</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var canvas = document.getElementById("canvas");
            var ctx = canvas.getContext("2d");

            var canvasOffset = $("#canvas").offset();
            var offsetX = canvasOffset.left;
            var offsetY = canvasOffset.top;

            var startX;
            var startY;
            var isDown = false;


            var pi2 = Math.PI * 2;
            var resizerRadius = 8;
            var rr = resizerRadius * resizerRadius;
            var draggingResizer = {
                x: 0,
                y: 0
            };
            var imageX = 50;
            var imageY = 50;
            var imageWidth, imageHeight, imageRight, imageBottom;
            var draggingImage = false;
            var startX;
            var startY;

            var img = new Image();
            img.onload = function () {
                imageWidth = 222;
                imageHeight = 259;
                imageRight = imageX + imageWidth;
                imageBottom = imageY + imageHeight
                draw(false, true);
            }
            img.src = "<?php echo e($image); ?>";


            function draw(withAnchors, withBorders) {

                // clear the canvas
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                ctx.beginPath();
                //Draw t-shirt body image
                var bodyImg = new Image();
                bodyImg.onload = function () {
                    bodyImgWidth = bodyImg.width;
                    bodyImgHeight = bodyImg.height;
                    bodyImgRight = imageWidth;
                    bodyImgBottom = imageHeight;
                    ctx.drawImage(bodyImg, 0, 0, bodyImg.width, bodyImg.height, 0, 0, bodyImgWidth, bodyImgHeight);
                }
                bodyImg.src = 'http://localhost/printify/public/shirt2.png';

                ctx.beginPath();
                // draw the image
                console.log('X1 = ' + imageX);
                console.log('Y1 = ' + imageY);
                console.log('imageWidth = ' + imageWidth);
                console.log('imageHeight = ' + imageHeight);

                // $('#x').val(imageX);
                // $('#y').val(imageY);
                // $('#width').val(imageWidth);
                // $('#height').val(imageHeight);
                // console.log(img.height);
                // console.log(imageWidth);
                // console.log(imageHeight);

                ctx.drawImage(img, 0, 0, img.width, img.height, imageX, imageY, imageWidth, imageHeight);

                // optionally draw the draggable anchors
                if (withAnchors) {
                    drawDragAnchor(imageX, imageY);
                    drawDragAnchor(imageRight, imageY);
                    drawDragAnchor(imageRight, imageBottom);
                    drawDragAnchor(imageX, imageBottom);
                }

                // optionally draw the connecting anchor lines
                if (withBorders) {
                    ctx.beginPath();
                    ctx.moveTo(imageX, imageY);
                    ctx.lineTo(imageRight, imageY);
                    ctx.lineTo(imageRight, imageBottom);
                    ctx.lineTo(imageX, imageBottom);
                    ctx.closePath();
                    ctx.stroke();
                }

            }

            function drawDragAnchor(x, y) {
                ctx.beginPath();
                ctx.arc(x, y, resizerRadius, 0, pi2, false);
                ctx.closePath();
                ctx.fill();
            }

            function anchorHitTest(x, y) {

                var dx, dy;

                // top-left
                dx = x - imageX;
                dy = y - imageY;
                if (dx * dx + dy * dy <= rr) {
                    return (0);
                }
                // top-right
                dx = x - imageRight;
                dy = y - imageY;
                if (dx * dx + dy * dy <= rr) {
                    return (1);
                }
                // bottom-right
                dx = x - imageRight;
                dy = y - imageBottom;
                if (dx * dx + dy * dy <= rr) {
                    return (2);
                }
                // bottom-left
                dx = x - imageX;
                dy = y - imageBottom;
                if (dx * dx + dy * dy <= rr) {
                    return (3);
                }
                return (-1);

            }


            function hitImage(x, y) {
                return (x > imageX && x < imageX + imageWidth && y > imageY && y < imageY + imageHeight);
            }


            function handleMouseDown(e) {
                startX = parseInt(e.clientX - offsetX);
                startY = parseInt(e.clientY - offsetY);
                draggingResizer = anchorHitTest(startX, startY);
                draggingImage = draggingResizer < 0 && hitImage(startX, startY);
            }

            function handleMouseUp(e) {
                draggingResizer = -1;
                draggingImage = false;
                draw(false, false);
            }

            function handleMouseOut(e) {
                handleMouseUp(e);
            }

            function handleMouseMove(e) {

                if (draggingResizer > -1) {

                    mouseX = parseInt(e.clientX - offsetX);
                    mouseY = parseInt(e.clientY - offsetY);

                    // resize the image
                    switch (draggingResizer) {
                        case 0:
                            //top-left
                            imageX = mouseX;
                            imageWidth = imageRight - mouseX;
                            imageY = mouseY;
                            imageHeight = imageBottom - mouseY;
                            break;
                        case 1:
                            //top-right
                            imageY = mouseY;
                            imageWidth = mouseX - imageX;
                            imageHeight = imageBottom - mouseY;
                            break;
                        case 2:
                            //bottom-right
                            imageWidth = mouseX - imageX;
                            imageHeight = mouseY - imageY;
                            break;
                        case 3:
                            //bottom-left
                            imageX = mouseX;
                            imageWidth = imageRight - mouseX;
                            imageHeight = mouseY - imageY;
                            break;
                    }

                    if(imageWidth<25){imageWidth=25;}
                    if(imageHeight<25){imageHeight=25;}

                    // set the image right and bottom
                    imageRight = imageX + imageWidth;
                    imageBottom = imageY + imageHeight;

                    // redraw the image with resizing anchors
                    draw(true, true);

                } else if (draggingImage) {

                    imageClick = false;

                    mouseX = parseInt(e.clientX - offsetX);
                    mouseY = parseInt(e.clientY - offsetY);

                    // move the image by the amount of the latest drag
                    var dx = mouseX - startX;
                    var dy = mouseY - startY;
                    imageX += dx;
                    imageY += dy;
                    imageRight += dx;
                    imageBottom += dy;
                    // reset the startXY for next time
                    startX = mouseX;
                    startY = mouseY;

                    // redraw the image with border
                    draw(true, true);

                }


            }

            //
            //
            // 360
            // 329
            //____________
            //
            // 7
            //
            //
            //
            //

            $("#canvas").mousedown(function (e) {
                handleMouseDown(e);
            });
            $("#canvas").mousemove(function (e) {
                handleMouseMove(e);
            });
            $("#canvas").mouseup(function (e) {
                handleMouseUp(e);
            });
            $("#canvas").mouseout(function (e) {
                handleMouseOut(e);
            });

            $('#submit').click(function() {
                var path =canvas.toDataURL();
                $('#path').val(path);
            });
        });
    </script>

    </head>
    <body>
        <div style="top: 50px; text-align:center">
            <canvas id="canvas" width=530 height=530></canvas>

            <form action="<?php echo e(route('saveFile')); ?>" method="post">
<!--                 <input type="hidden" name="x" id="x">
                <input type="hidden" name="y" id="y"> -->
<!--                 <input type="hidden" name="width" id="width">
                <input type="hidden" name="height" id="height"> -->
                <input type="hidden" name="path" id="path">
                <input type="submit" value="save" id="submit">
            </form>
        </div>
    </body>
</html>
