<?php

namespace App\Models;

use App\Actions\NotePlan\TransformMarkdownToHtml;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class Note extends Model
{
    use HasFactory;

    protected $guarded = [
        'slug',
        'encrypted_title',
        'encrypted_content',
        'user_id',
    ];

    private ?Encrypter $noteEncrypter = null;
    private ?string $notePassword = null;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPasswordValid(): bool
    {
        try {
            $title = $this->title;
        } catch (\Exception $exception) {
            // the encrypter will fail when the password is not set or it is invalid
            return false;
        }
        return true;
    }

    public function setPasswordAttribute(string $value)
    {
        if (!$value) {
            throw new \Exception('The password cannot be empty');
        }

        $this->notePassword = $value;

        while (Str::length($value) < 32) $value .= $value;
        $password = Str::substr($value, 0, 32);

        $this->noteEncrypter = new Encrypter($password, 'AES-256-CBC');
    }

    public function getPasswordAttribute(): string
    {
        return $this->notePassword;
    }

    public function getTitleAttribute(): string
    {
        return $this->getDecrypted('title');
    }

    public function setTitleAttribute(string $value): void
    {
        $this->setEncrypted('title', $value);
    }

    public function getContentAttribute(): string
    {
        return $this->getDecrypted('content');
    }

    public function setContentAttribute(string $value): void
    {
        $this->setEncrypted('content', $value);
    }

    public function getHtmlContentAttribute(): string
    {
        return app(TransformMarkdownToHtml::class)->execute($this->content);
    }

    private function setEncrypted(string $property, string $value): void
    {
        if (!$this->noteEncrypter) {
            throw new \Exception('The password is needed to encrypt the note ' . $property);
        }

        $encryptedProperty = 'encrypted_' . $property;
        $this->{$encryptedProperty} = $this->noteEncrypter->encrypt($value);
    }

    private function getDecrypted(string $property): string
    {
        if (!$this->noteEncrypter) {
            throw new \Exception('The password is needed to decrypt the note ' . $property);
        }

        $encryptedProperty = $this->{'encrypted_' . $property};
        if (!$encryptedProperty) {
            return '';
        }

        return $this->noteEncrypter->decrypt($encryptedProperty);
    }
}
