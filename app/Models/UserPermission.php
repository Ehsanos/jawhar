<?phpnamespace App\Models;use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\SoftDeletes;class UserPermission extends Model{    use SoftDeletes;    protected $table = 'user_permissions';    protected $guarded = [];    public function user()    {        return $this->belongsTo(User::class);    }}