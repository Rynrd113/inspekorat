<?php

namespace Tests\Browser\Traits;

use Laravel\Dusk\Browser;

trait InteractsWithForms
{
    /**
     * Fill form dengan data array
     */
    protected function fillForm(Browser $browser, array $data)
    {
        foreach ($data as $field => $value) {
            if (is_array($value)) {
                // Handle untuk select options atau checkboxes
                if ($value['type'] === 'select') {
                    $browser->select($field, $value['value']);
                } elseif ($value['type'] === 'radio') {
                    $browser->radio($field, $value['value']);
                } elseif ($value['type'] === 'checkbox') {
                    if ($value['value']) {
                        $browser->check($field);
                    } else {
                        $browser->uncheck($field);
                    }
                }
            } else {
                $browser->type($field, $value);
            }
        }

        return $browser;
    }

    /**
     * Submit form dan tunggu response
     */
    protected function submitForm(Browser $browser, string $submitButton = 'Submit')
    {
        $browser->press($submitButton)
                ->waitForReload()
                ->waitForLoadingToFinish();

        return $browser;
    }

    /**
     * Assert form validation errors
     */
    protected function assertFormValidationErrors(Browser $browser, array $fields)
    {
        foreach ($fields as $field => $message) {
            $browser->assertSee($message)
                    ->assertPresent(".error-{$field}, .invalid-feedback");
        }

        return $browser;
    }

    /**
     * Assert required field validation
     */
    protected function assertRequiredFieldValidation(Browser $browser, string $field, string $submitButton = 'Submit')
    {
        $browser->clear($field)
                ->press($submitButton)
                ->waitFor('.error-' . $field, 5)
                ->assertSee('field is required')
                ->assertPresent('.error-' . $field);

        return $browser;
    }

    /**
     * Test email format validation
     */
    protected function testEmailValidation(Browser $browser, string $emailField)
    {
        $invalidEmails = [
            'invalid-email',
            'invalid@',
            '@invalid.com',
            'invalid.com',
            'invalid@.com'
        ];

        foreach ($invalidEmails as $email) {
            $browser->clear($emailField)
                    ->type($emailField, $email)
                    ->press('Submit')
                    ->waitFor('.error-' . $emailField, 5)
                    ->assertSee('valid email');
        }

        return $browser;
    }

    /**
     * Test character limit validation
     */
    protected function testCharacterLimitValidation(Browser $browser, string $field, int $maxLength)
    {
        $longText = str_repeat('a', $maxLength + 1);
        
        $browser->clear($field)
                ->type($field, $longText)
                ->press('Submit')
                ->waitFor('.error-' . $field, 5)
                ->assertSee('maximum');

        return $browser;
    }

    /**
     * Test numeric validation
     */
    protected function testNumericValidation(Browser $browser, string $field)
    {
        $nonNumericValues = ['abc', '12abc', 'abc12', '!@#'];

        foreach ($nonNumericValues as $value) {
            $browser->clear($field)
                    ->type($field, $value)
                    ->press('Submit')
                    ->waitFor('.error-' . $field, 5)
                    ->assertSee('numeric');
        }

        return $browser;
    }

    /**
     * Test password confirmation validation
     */
    protected function testPasswordConfirmationValidation(Browser $browser)
    {
        $browser->type('password', 'password123')
                ->type('password_confirmation', 'differentpassword')
                ->press('Submit')
                ->waitFor('.error-password_confirmation', 5)
                ->assertSee('password confirmation does not match');

        return $browser;
    }

    /**
     * Test password strength validation
     */
    protected function testPasswordStrengthValidation(Browser $browser, string $passwordField)
    {
        $weakPasswords = ['123', 'password', '1234567', 'abcdefgh'];

        foreach ($weakPasswords as $password) {
            $browser->clear($passwordField)
                    ->type($passwordField, $password)
                    ->press('Submit')
                    ->waitFor('.error-' . $passwordField, 5)
                    ->assertSee('password must be');
        }

        return $browser;
    }

    /**
     * Test unique validation
     */
    protected function testUniqueValidation(Browser $browser, string $field, string $existingValue)
    {
        $browser->clear($field)
                ->type($field, $existingValue)
                ->press('Submit')
                ->waitFor('.error-' . $field, 5)
                ->assertSee('already been taken');

        return $browser;
    }

    /**
     * Test form auto-save functionality
     */
    protected function testFormAutoSave(Browser $browser, array $formData)
    {
        // Fill form data
        $this->fillForm($browser, $formData);
        
        // Wait for auto-save
        $browser->pause(3000);
        
        // Refresh page
        $browser->refresh();
        
        // Assert data is still there
        foreach ($formData as $field => $value) {
            if (!is_array($value)) {
                $browser->assertInputValue($field, $value);
            }
        }

        return $browser;
    }

    /**
     * Test form dirty state warning
     */
    protected function testFormDirtyStateWarning(Browser $browser, array $formData)
    {
        // Fill form dengan data
        $this->fillForm($browser, $formData);
        
        // Try to navigate away
        $browser->script('window.location.href = "/admin/dashboard";');
        
        // Assert confirmation dialog appears
        $browser->waitForDialog(5)
                ->assertDialogOpened()
                ->dismissDialog();

        return $browser;
    }

    /**
     * Clear all form fields
     */
    protected function clearAllFormFields(Browser $browser, array $fields)
    {
        foreach ($fields as $field) {
            $browser->clear($field);
        }

        return $browser;
    }

    /**
     * Assert form is empty
     */
    protected function assertFormIsEmpty(Browser $browser, array $fields)
    {
        foreach ($fields as $field) {
            $browser->assertInputValue($field, '');
        }

        return $browser;
    }

    /**
     * Fill form dengan data invalid dan test validation
     */
    protected function testCompleteFormValidation(Browser $browser, array $validationRules)
    {
        foreach ($validationRules as $field => $rules) {
            foreach ($rules as $rule => $data) {
                switch ($rule) {
                    case 'required':
                        $this->assertRequiredFieldValidation($browser, $field);
                        break;
                    case 'email':
                        $this->testEmailValidation($browser, $field);
                        break;
                    case 'max':
                        $this->testCharacterLimitValidation($browser, $field, $data);
                        break;
                    case 'numeric':
                        $this->testNumericValidation($browser, $field);
                        break;
                    case 'unique':
                        $this->testUniqueValidation($browser, $field, $data);
                        break;
                }
            }
        }

        return $browser;
    }
}
