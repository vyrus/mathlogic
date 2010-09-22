<html>
<head>
    <style>
        .expression-wrapper {
            margin-bottom: 10px;
        }

        .expression {
            font: 25px Arial;
        }

        .expression a {
            color: #000;
            text-decoration: none;
        }

        .expr {
            padding: 0 1px;
        }

        .selected {
            background-color: #e5e5e5;
            /*border-bottom: 1px dashed #000;*/
        }
    </style>
    <script type="text/javascript" src="jquery-1.4.2.js"></script>
</head>

<body>
    <script type="text/javascript">
        $(function() {
            $('span.expr a').each(function(idx, op_link) {
                $(op_link)
                    .mouseover(function(e) {
                        $(e.target).parent().addClass('selected');
                    })
                    .mouseout(function(e) {
                        $(e.target).parent().removeClass('selected')
                    })
            });
        });
    </script>

    <div style="text-align: center;">
        <?php foreach ($expressions as $key => $expr): ?>
        <div class="expression-wrapper">
            <span class="expression">
                <?php echo $expr->render() ?>
            </span>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>