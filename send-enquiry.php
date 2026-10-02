<?php

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);

    exit;
}

// Your email address
$to = 'info@fairview.co.ls';

// Get and sanitize form data
$name = trim($_POST['name'] ?? '');
$organization = trim($_POST['organization'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate required fields
if ($name === '' || $email === '' || $service === '' || $message === '') {

    echo json_encode([
        'success' => false,
        'message' => 'Please complete all required fields.'
    ]);

    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid email address.'
    ]);

    exit;
}

// Prevent header injection
$name = str_replace(["\r", "\n"], '', $name);
$email = str_replace(["\r", "\n"], '', $email);
$organization = str_replace(["\r", "\n"], '', $organization);
$phone = str_replace(["\r", "\n"], '', $phone);
$service = str_replace(["\r", "\n"], '', $service);

// Email subject
$subject = 'New Consultation Enquiry - Fairview Solutions';

// Escape values for HTML
$safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$safeOrganization = htmlspecialchars($organization ?: 'Not provided', ENT_QUOTES, 'UTF-8');
$safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$safePhone = htmlspecialchars($phone ?: 'Not provided', ENT_QUOTES, 'UTF-8');
$safeService = htmlspecialchars($service, ENT_QUOTES, 'UTF-8');
$safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

// Branded HTML email
$emailBody = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Consultation Enquiry</title>
</head>

<body style="margin:0; padding:0; background-color:#F4F7F6; font-family:Arial, Helvetica, sans-serif; color:#111111;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F4F7F6; padding:40px 15px;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; width:100%; background:#FFFFFF; border-radius:16px; overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <td style="background-color:#0A2670; padding:28px 30px;">

                            <div style="font-size:24px; font-weight:bold; color:#FFFFFF;">
                                Fairview <span style="color:#079B5A;">Solutions</span>
                            </div>

                            <div style="margin-top:6px; font-size:13px; color:#DDE5E1;">
                                Consulting • Training • ICT • Supplies
                            </div>

                        </td>
                    </tr>

                    <!-- Accent line -->
                    <tr>
                        <td style="height:5px; background-color:#079B5A;"></td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:35px 30px;">

                            <h2 style="margin:0 0 8px 0; font-size:22px; color:#111111;">
                                New Consultation Enquiry
                            </h2>

                            <p style="margin:0 0 25px 0; color:#5F6B66; font-size:14px; line-height:1.6;">
                                A new enquiry has been submitted through the Fairview Solutions website.
                            </p>

                            <!-- Customer details -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #DDE5E1; border-radius:12px; overflow:hidden;">

                                <tr>
                                    <td colspan="2" style="background:#F4F7F6; padding:14px 16px; font-weight:bold; color:#123DA6; font-size:14px;">
                                        Contact Details
                                    </td>
                                </tr>

                                <tr>
                                    <td width="35%" style="padding:13px 16px; border-bottom:1px solid #DDE5E1; color:#66736E; font-size:13px;">
                                        Name
                                    </td>
                                    <td style="padding:13px 16px; border-bottom:1px solid #DDE5E1; font-weight:600; font-size:13px; color:#111111;">
                                        ' . $safeName . '
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:13px 16px; border-bottom:1px solid #DDE5E1; color:#66736E; font-size:13px;">
                                        Organization
                                    </td>
                                    <td style="padding:13px 16px; border-bottom:1px solid #DDE5E1; font-size:13px; color:#111111;">
                                        ' . $safeOrganization . '
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:13px 16px; border-bottom:1px solid #DDE5E1; color:#66736E; font-size:13px;">
                                        Email
                                    </td>
                                    <td style="padding:13px 16px; border-bottom:1px solid #DDE5E1; font-size:13px;">
                                        <a href="mailto:' . $safeEmail . '" style="color:#123DA6; text-decoration:none;">
                                            ' . $safeEmail . '
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:13px 16px; border-bottom:1px solid #DDE5E1; color:#66736E; font-size:13px;">
                                        Phone
                                    </td>
                                    <td style="padding:13px 16px; border-bottom:1px solid #DDE5E1; font-size:13px; color:#111111;">
                                        ' . $safePhone . '
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:13px 16px; color:#66736E; font-size:13px;">
                                        Service
                                    </td>
                                    <td style="padding:13px 16px; font-weight:600; color:#079B5A; font-size:13px;">
                                        ' . $safeService . '
                                    </td>
                                </tr>

                            </table>

                            <!-- Message -->
                            <div style="margin-top:25px;">

                                <div style="font-size:14px; font-weight:bold; color:#123DA6; margin-bottom:10px;">
                                    Enquiry Message
                                </div>

                                <div style="background:#F4F7F6; border-left:4px solid #079B5A; padding:16px; border-radius:8px; font-size:14px; line-height:1.7; color:#111111;">
                                    ' . $safeMessage . '
                                </div>

                            </div>

                            <!-- Reply button -->
                            <div style="margin-top:28px; text-align:center;">

                                <a href="mailto:' . $safeEmail . '"
                                   style="display:inline-block; background:#123DA6; color:#FFFFFF; text-decoration:none; padding:13px 25px; border-radius:30px; font-size:14px; font-weight:bold;">
                                    Reply to Enquiry →
                                </a>

                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#111111; padding:25px 30px; text-align:center;">

                            <div style="font-size:14px; font-weight:bold; color:#FFFFFF;">
                                Fairview <span style="color:#079B5A;">Solutions</span>
                            </div>

                            <div style="margin-top:8px; font-size:12px; line-height:1.6; color:#B8C2BE;">
                                Consulting • Training • ICT • Supplies
                            </div>

                            <div style="margin-top:12px; font-size:11px; color:#7F8985;">
                                This enquiry was submitted through the Fairview Solutions website.
                            </div>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
';

// Email headers
$headers = [];
$headers[] = 'From: Fairview Website <info@fairview.co.ls>';
$headers[] = 'Reply-To: ' . $email;
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/html; charset=UTF-8';

// Send email
$sent = mail(
    $to,
    $subject,
    $emailBody,
    implode("\r\n", $headers)
);

// Return response to AJAX
if ($sent) {

    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your enquiry has been sent successfully. We will get back to you shortly.'
    ]);

    exit;
}

// Failed
echo json_encode([
    'success' => false,
    'message' => 'We could not send your enquiry. Please try again or contact us directly.'
]);

exit;