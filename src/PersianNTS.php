<?php

namespace Dena\PersianNTS;

use Dena\PersianNTS\Classes\PHPMP3;
use Dena\PersianNTS\Helpers\MP3Helper;

use Exception;
use Illuminate\Support\Facades\Validator;

class PersianNTS
{
    /** Currency constants */
    const RIAL  = 'rial';
    const TOMAN = 'toman';

    /**
     * Number variable
     *
     * @var integer
     */
    protected $number;

    /**
     * Currency variable
     *
     * @var string
     */
    protected $currency;

    /**
     * Audio Contents variable
     *
     * @var string
     */
    protected $audio_contents;

    /**
     * Save Path variable
     *
     * @var string
     */
    protected $savepath;

    /**
     * Constructor function
     *
     * @param integer $number
     */
    public function __construct(int $number = null)
    {
        $this->number = $number;

        $this->audio_contents = MP3Helper::empty();

        $this->savepath = __DIR__.'/../resources/sounds/temp.mp3';
    }

    /**
     * Set Number function
     *
     * @param integer $number
     * @return void
     */
    public function setNumber(int $number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Set Number function
     *
     * @param string $path
     * @return void
     */
    public function setSavePath(string $path)
    {
        $this->savepath = $path;

        return $this;
    }

    /**
     * Get Number function
     *
     * @param string $path
     * @return void
     */
    public function getSavePath()
    {
        return $this->savepath;
    }

    /**
     * Validator function
     *
     * @return void
     */
    public function validator()
    {
        $validator = Validator::make(['number' => $this->number], [
            'number' => 'required|integer|min:-999999999999|max:999999999999',
        ]);

        if ($validator->fails()) {
            throw new Exception('Input number is not valid.');
        }
    }

    /**
     * Generate Audio function
     *
     * @return void
     */
    private function generateAudio()
    {
        if ($this->number < 0) {
            $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__.'/../resources/sounds/math/minus-continues.mp3');
        }

        $tmp_number = abs($this->number);

        $bundles = array_reverse(str_split(strrev(strval($tmp_number)), 3));
        foreach ($bundles as $bundle_key => $bundle) {
            $tmp_bundle_number = strrev($bundle);
            $digits = str_split(strrev($bundle));
            foreach ($digits as $key => $digit) {
                $digit_num = $key + 1;

                $digit = intval($digit);
                if (!$digit) {
                    continue;
                }              

                switch (true) {
                    case (((count($digits) == 2 && $digit_num == 1) || (count($digits) == 3 && $digit_num == 2)) &&
                          ($tmp_bundle_number % 100 < 20 && $tmp_bundle_number % 100 >= 10)):
                        $tmp_digit = $tmp_bundle_number % 100;
                        if ($digit_num == 2) {
                            $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__."/../resources/sounds/numbers/{$tmp_digit}.mp3");
                        }
                        break;
                    case (((count($digits) == 1 && $digit_num == 1) ||
                          (count($digits) == 2 && $digit_num == 2) ||
                          (count($digits) == 3 && $digit_num == 3)) &&
                          ($tmp_bundle_number % 100 >= 20 || $tmp_bundle_number % 100 < 10)):
                        $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__."/../resources/sounds/numbers/{$digit}.mp3");
                        break;
                    case ((count($digits) == 2 && $digit_num == 1) ||
                          (count($digits) == 3 && $digit_num == 2)):
                        if ($tmp_bundle_number % 10 == 0) {
                            $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__."/../resources/sounds/numbers/{$digit}0.mp3");
                        } else {
                            $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__."/../resources/sounds/numbers/{$digit}0-continues.mp3");
                        }
                        break;
                    case (count($digits) == 3 && $digit_num == 1):
                        if ($tmp_bundle_number % 100 == 0) {
                            $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__."/../resources/sounds/numbers/{$digit}00.mp3");
                        } else {
                            $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__."/../resources/sounds/numbers/{$digit}00-continues.mp3");
                        }
                        break;
                }
            }

            if (intval($bundle) == 0) {
                continue;
            }

            $bundle_num = $bundle_key + 1;
            switch (true) {
                case ((count($bundles) == 2 && $bundle_num == 1) ||
                      (count($bundles) == 3 && $bundle_num == 2) ||
                      (count($bundles) == 4 && $bundle_num == 3)):
                    if ($tmp_number % 1000 == 0) {
                        $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__."/../resources/sounds/numbers/1000.mp3");
                    } else {
                        $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__."/../resources/sounds/numbers/1000-continues.mp3");
                    }
                    break;
                case ((count($bundles) == 3 && $bundle_num == 1) ||
                      (count($bundles) == 4 && $bundle_num == 2)):
                    if ($tmp_number % 1000000 == 0) {
                        $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__."/../resources/sounds/numbers/1000000.mp3");
                    } else {
                        $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__."/../resources/sounds/numbers/1000000-continues.mp3");
                    }
                    break;
                case (count($bundles) == 4 && $bundle_num == 1):
                    if ($tmp_number % 1000000000 == 0) {
                        $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__."/../resources/sounds/numbers/1000000000.mp3");
                    } else {
                        $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__."/../resources/sounds/numbers/1000000000-continues.mp3");
                    }
                    break;
            }
        }
    }

    /**
     * Generate Audio function
     *
     * @return void
     */
    private function appendCurrency()
    {
        if ($this->currency === self::RIAL) {
            $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__.'/../resources/sounds/currencies/rial.mp3');
        } elseif ($this->currency === self::TOMAN) {
            $this->audio_contents = MP3Helper::mergeBehind($this->audio_contents, __DIR__.'/../resources/sounds/currencies/toman.mp3');
        }
    }
    
    /**
     * Get Speech function
     *
     * @return void
     */
    public function getSpeech()
    {
        try {
            $this->validator();

            $this->generateAudio();

            $this->appendCurrency();
        } catch (Exception $ex) {
            throw $ex;
        }
        
        return $this;
    }

    /**
     * Save function
     *
     * @return void
     */
    public function save()
    {
        try {
            $this->audio_contents->save($this->savepath);
        } catch (Exception $ex) {
            throw $ex;
        }

        return $this;
    }

    /**
     * To Toman function
     *
     * @return void
     */
    public function toToman()
    {
        $this->currency = self::TOMAN;

        return $this;
    }

    /**
     * To Rial function
     *
     * @return void
     */
    public function toRial()
    {
        $this->currency = self::RIAL;

        return $this;
    }
}