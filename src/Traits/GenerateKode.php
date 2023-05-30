<?php

namespace EasyLaravelPackage\Traits;


trait GenerateKode
{
  /**
   * Boot function from Laravel.
   */
  protected static function boot()
  {
    parent::boot();
    static::creating(function ($model) {
      if (empty($model->{$model->getKeyName()})) {
        $model->{$model->getKeyName()} = $model->uid();
      }
    });
  }

  /**
   * Get the value indicating whether the IDs are incrementing.
   *
   * @return bool
   */
  public function getIncrementing()
  {
    return false;
  }

  /**
   * Get the auto-incrementing key type.
   *
   * @return string
   */
  public function getKeyType()
  {
    return 'string';
  }

  /**
   * gen uid
   * @param int $limit
   * @return false|string
   */
  public function uid($limit = 16)
  {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
  }

  public function kode($lastKode, $lenght, $start, $awalKode = NULL, $split = NULL)
  {
    $kode = substr($lastKode, $start);
    $angka = (int)$kode;
    $angka_baru = $awalKode . $split . str_repeat("0", $lenght - strlen($angka + 1)) . ($angka + 1);
    return $angka_baru;
  }
}
