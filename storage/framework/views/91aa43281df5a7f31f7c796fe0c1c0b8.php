<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($subject); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }

        .message-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .footer {
            background-color: #f3f4f6;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-radius: 0 0 8px 8px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 style="margin: 0;">CLSU Faculty Hiring Evaluation System</h1>
    </div>

    <div class="content">
        <p>Dear <strong><?php echo e($applicantName); ?></strong>,</p>

        <p>You have received a new notification from the CLSU Faculty Hiring Evaluation System.</p>

        <div class="message-content">
            <?php echo $notificationMessage; ?>

            <!-- renamed here -->
        </div>

        <a href="<?php echo e(url('/applicant/notifications')); ?>" class="button">View All Notifications</a>

        <p>If you have any questions, please don't hesitate to contact us.</p>

        <p>Best regards,<br>
            <strong>CLSU HR Department</strong>
        </p>
    </div>

    <div class="footer">
        <p>This is an automated message from CLSU Faculty Hiring Evaluation System.</p>
        <p>Please do not reply to this email.</p>
        <p>&copy; <?php echo e(date('Y')); ?> Central Luzon State University. All rights reserved.</p>
    </div>
</body>

</html><?php /**PATH C:\xampp\htdocs\CLSU-FHES\resources\views\emails\notification.blade.php ENDPATH**/ ?>