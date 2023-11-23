<?php

namespace App\Models;

use App\Models\File;
use NumberFormatter;
use App\Models\Company;
use App\Models\Language;
use App\Enums\PostStatusEnum;
use App\Models\ObjectLanguage;
use App\Enums\SystemCacheKeyEnum;
use App\Enums\PostCurrencySalaryEnum;
use App\Enums\PostRemotableEnum;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\HtmlString;


/**
 * App\Models\Post
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $company_id
 * @property string $job_tittle
 * @property string|null $district
 * @property string|null $city
 * @property int|null $remotable
 * @property int|null $can_parttime
 * @property float|null $min_salary
 * @property float|null $max_salary
 * @property int|null $currency_salary
 * @property string|null $requirement
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property int|null $number_applicants
 * @property int $status
 * @property int $is_pinned
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Company|null $company
 * @property-read File|null $file
 * @property-read mixed $currency_salary_code
 * @property-read bool $is_not_available
 * @property-read mixed $location
 * @property-read string $remotable_name
 * @property-read string $salary
 * @property-read mixed $status_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Language> $languages
 * @property-read int|null $languages_count
 * @method static \Illuminate\Database\Eloquent\Builder|Post approved()
 * @method static \Database\Factories\PostFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Post findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Post indexHomePage($filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCanParttime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCurrencySalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereIsPinned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereJobTittle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereMaxSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereMinSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereNumberApplicants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereRemotable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereRequirement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = [
        'company_id',
        'job_tittle',
        'levels',
        'city',
        'district',
        'status',
        'remotable',
        'can_parttime',
        'min_salary',
        'max_salary',
        'currency_salary',
        'requirement',
        'start_date',
        'end_date',
        'number_applicants',
        'is_pinnned',
        'slug',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    protected static function booted(): void
    {

        // thêm vào lúc đang tạo
        static::creating(function ($object) {
            $object->user_id = user()->id;
            $object->status  = PostStatusEnum::getByRole();
        });
        static::saved(function ($object) {
            $city = $object->city;
            $arr = explode(', ', $city);
            $arrCity = getAndCachePostCities();
            foreach ($arr as $item) {
                if (in_array($item, $arrCity)) {
                    continue;
                }
                $arrCity[] = $item;
            }

            cache()->put(SystemCacheKeyEnum::POST_CITIES, $arrCity);
        });
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'job_tittle'
            ]
        ];
    }

    public function languages(): MorphToMany
    {
        return $this->morphToMany(
            Language::class,
            'object',
            ObjectLanguage::class,
            'object_id',
            'language_id',
        );
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function file(): HasOne
    {
        return $this->HasOne(File::class);
    }

    public function getCurrencySalaryCodeAttribute()
    {
        return PostCurrencySalaryEnum::getKey($this->currency_salary);
    }

    public function getStatusNameAttribute()
    {
        return PostStatusEnum::getKey($this->status);
    }

    public function getLocationAttribute()
    {
        if (!empty($this->district)) {
            return $this->district . ' - ' . $this->city;
        }
        return $this->city;
    }

    public function getSalaryAttribute(): string
    {
        // format currency salary
        $val = $this->currency_salary;
        $key = PostCurrencySalaryEnum::getKey($val);
        $locale = PostCurrencySalaryEnum::getLocaleByVal($val);
        $format = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $rate   = Config::getByKey($key);

        $res = '';

        // solve min and max salary
        if (!is_null($this->min_salary) && !is_null($this->max_salary)) {
            $min_salary = (int) round($this->min_salary * $rate);
            $max_salary = (int) round($this->max_salary * $rate);



            $min_salary = $format->formatCurrency($min_salary, $key);
            $max_salary = $format->formatCurrency($max_salary, $key);

            $min_salary = formatCurrencySalary($min_salary, $key);
            $max_salary = formatCurrencySalary($max_salary, $key);



            $res .= $min_salary . ' - ' . $max_salary;
        } else if (!is_null($this->min_salary)) {
            $min_salary = (int) round($this->min_salary);
            $min_salary = $format->formatCurrency($min_salary *  $rate, $key);
            $min_salary = formatCurrencySalary($min_salary, $key);

            $res .= __('frontpage.from_salary') . ' ' . $min_salary;
        } else if (!is_null($this->max_salary)) {
            $max_salary = (int) round($this->max_salary);
            $max_salary = $format->formatCurrency($max_salary *  $rate, $key);
            $max_salary = formatCurrencySalary($max_salary, $key);

            $res .= __('frontpage.to_salary') . ' ' . $max_salary;
        } else {
            $res = 'the deal';
        }
        return $res;
    }

    public function getRemotableNameAttribute(): string
    {
        return str_replace('_', ' ', PostRemotableEnum::getKey($this->remotable));
    }

    public function getRequirementAttribute()
    {
        return new HtmlString($this->attributes['requirement']);
    }

    public function getIsNotAvailableAttribute(): bool
    {
        if (empty($this->start_date)) {
            return false;
        }
        if (empty($this->end_date)) {
            return false;
        }

        return !now()->between($this->start_date, $this->end_date);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', PostStatusEnum::ADMIN_APPROVED);
    }

    public function scopeIndexHomePage($query, $filters)
    {
        return $query
            ->with([
                'languages',
                'company' => function ($q) {
                    $q->select([
                        'id',
                        'name',
                        'logo',
                    ]);
                }
            ])
            ->approved()
            ->when(isset($filters['cities']), function ($q) use ($filters) {
                $q->where(function ($q) use ($filters) {
                    foreach ($filters['cities'] as $searchCity) {
                        $q->orWhere('city', 'like', '%' . $searchCity . '%');
                    }
                    $q->orWhereNull('city');
                });
            })
            ->when(isset($filters['min_salary']), function ($q) use ($filters) {
                $q->where(function ($q) use ($filters) {
                    $q->orWhere('min_salary', '>=', $filters['min_salary']);
                    $q->orWhereNull('min_salary');
                });
            })
            ->when(isset($filters['max_salary']), function ($q) use ($filters) {
                $q->where(function ($q) use ($filters) {
                    $q->orWhere('max_salary', '>=', $filters['max_salary']);
                    $q->orWhereNull('max_salary');
                });
            })
            ->when(isset($filters['remotable']), function ($q) use ($filters) {
                $q->where('remotable', $filters['remotable']);
            })
            ->when(isset($filters['can_parttime']), function ($q) use ($filters) {
                $q->where('can_parttime', $filters['can_parttime']);
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('id');
    }
}
