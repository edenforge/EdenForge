require 'vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_live_YOUR_SECRET_KEY');

header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);

$line_items = array_map(function($item) {
    return [
        'price_data' => [
            'currency' => 'gbp',
            'product_data' => ['name' => $item['name']],
            'unit_amount' => $item['price'] * 100, // in pence
        ],
        'quantity' => $item['quantity'],
    ];
}, $input['cart']);

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => $line_items,
    'mode' => 'payment',
    'success_url' => 'https://yourdomain.com/success.html',
    'cancel_url' => 'https://yourdomain.com/cancel.html',
]);

echo json_encode(['id' => $session->id]);
