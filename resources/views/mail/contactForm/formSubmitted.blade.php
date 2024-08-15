<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-weight: 800;
            color: #333;
            font-size: 24px;
            margin-top: 0;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        ul li {
            padding: 8px 0;
            font-size: 16px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        p {
            margin: 20px 0;
            font-size: 16px;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 20px;
            }

            ul li {
                font-size: 14px;
            }

            p {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>
            Hey Pablo, {{ $data['name'] ?? 'No Name' }} has sent you a message
        </h1>

        <ul>
            <li>Reply Email: <a
                    href="mailto:{{ $data['email'] ?? 'no-reply@example.com' }}">{{ $data['email'] ?? 'No Email' }}</a>
            </li>
            <li>Subject: {{ $data['subject'] ?? 'No Subject' }}</li>
            <li>Message: {{ $data['message'] ?? 'No Message' }}</li>
        </ul>

        <p>
            This user is expecting a reply from you within 24-48 hours
        </p>
    </div>
</body>

</html>
