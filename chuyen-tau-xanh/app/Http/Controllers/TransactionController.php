namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\User;
use App\Events\TransactionStatusUpdated;

class TransactionQuery{}
class TransactionController extends Controller
{
public function index(Request $request, $userId)
{
$filter = new TransactionQuery();
$query = $filter->transform($request);
$query = Transaction::where($query);
$includeOrders = request()->query('includeOrders');
if ($includeOrders) {
$query->with('orders');
}
$transactions = $query->where('User_ID', $userId)->get();
return new TransactionCollection($transactions);
}
public function show($transactionId)
{
$query = Transaction::query();
$query->where('Transaction_ID', $transactionId);
if ($query) {
$includeOrders = request()->query('includeOrders');
if ($includeOrders) {
$query->with('orders');
}
$transactions = $query->get();
return new TransactionCollection($transactions);
}
return response()->json(['messsage' => 'Not found'], 404);
}
public function create(Request $request)
{
$request->validate([
'userId' => ['required'],
'name' => ['sometimes', 'required'],
'phone' => ['sometimes', 'required'],
'address' => ['sometimes', 'required'],
'status' => ['sometimes', 'required'],
'totalMoney' => ['sometimes', 'required'],
'paymentMethod' => ['sometimes', 'required'],
'note' => ['sometimes', 'required']
]);
$userId = $request->userId;
$transaction = Transaction::query()->where('User_ID', $userId)->where('Status', 0)->first();
if ($transaction) return response()->json(['message' => 'Invalid']);
$transaction = new Transaction;
$transaction->User_ID = $userId;
$user = User::find($userId);
if ($request->name) $transaction->Name = $request->name;
else $transaction->Name = $user->Name;
if ($request->phone) $transaction->Phone = $request->phone;
else $transaction->Phone = $user->Phone;
if ($request->address) $transaction->Address = $request->address;
else $transaction->Address = $user->Address;
if ($request->status) $transaction->Status = $request->status;
if ($request->totalMoney) $transaction->Total_money = $request->totalMoney;
if ($request->paymentMethod) $transaction->Payment_method = $request->paymentMethod;
if ($request->note) $transaction->Note = $request->note;
$transaction->save();
$id = $transaction->Transaction_ID;
return response()->json(['transactionId' => $id, 'message' => 'Success']);
}

public function update(UpdateTransactionRequest $request, $transactionId)
{

$transaction = Transaction::where('Transaction_ID', $transactionId)->first();
if ($transaction) {
$data = array_filter($request->all(), function ($value) {
return $value !== null && $value !== '';
});
$transaction->update($data);
if ($transaction->Status == 9)
event(new TransactionStatusUpdated($transaction));
} else return response()->json(['message' => 'Not found'], 404);
return response()->json(['message' => 'Success']);
}
public function destroy($id)
{
$transaction = Transaction::where('Transaction_ID', $id)->first();
if ($transaction) {
$transaction->delete($transaction);
return response()->json(['message' => 'Success']);
}
return response()->json(['message' => 'Not found'], 404);
}
}