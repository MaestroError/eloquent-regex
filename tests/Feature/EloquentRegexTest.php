<?php

use Maestroerror\EloquentRegex\EloquentRegex;

// Custom Pattern tests:

it('reproduces alt prefix pattern from HSA using wrapper', function () {
    $regex = EloquentRegex::builder()->start()
        ->exact("alt=")
        ->group(function ($pattern) {
            $pattern->doubleQuote()->orPattern(function ($pattern) {
                $pattern->singleQuote();
            });
        })->toRegex();

    expect($regex)->toBe("alt\=(\"|')");
});

it('reproduces hashtag prefix pattern from HSA using wrapper', function () {
    $regex = EloquentRegex::builder()->start()
        ->lookBehind(function ($pattern) {
            $pattern->charSet(function ($pattern) {
                $pattern->doubleQuote()->closeAngleBracket()->addRawRegex("\\s");
            });
        })->hash()->toRegex(); 

    expect($regex)->toBe('(?<=["\>\s])\#');
});

it('reproduces Text suffix pattern from HSA using wrapper', function () {
    $regex = EloquentRegex::builder()->start()
        ->openAngleBracket()->slash()->alphanumericRange(0, 10)->closeAngleBracket()
        ->toRegex();

    expect($regex)->toBe('\<\/[a-zA-Z0-9]{0,10}\>');
});

it('constructs regex for simple email validation using wrapper', function () {
    $regex = EloquentRegex::builder()->start()
        ->textLowercase()
        ->atSymbol()
        ->textLowercase()
        ->dot()
        ->textLowercaseRange(2, 4)
        ->toRegex();

    expect($regex)->toBe('[a-z]+@[a-z]+\.[a-z]{2,4}');
});

it('constructs regex for URL validation using wrapper', function () {
    $regex = EloquentRegex::builder()->pattern()
        ->exact(['http', 'https'])
        ->colon()
        ->doubleSlash()
        ->text()
        ->dot()
        ->text()
        ->toRegex();

    expect($regex)->toBe('(?:http|https)\:\/\/[a-zA-Z]+\.[a-zA-Z]+');
});

it('constructs regex for specific phone number format using wrapper', function () {
    $regex = EloquentRegex::builder()->pattern(function ($p) {
            $p->openParenthesis()->digits(3)->closeParenthesis()
            ->space()
            ->digits(3)->dash()->digits(4);
        })->toRegex();

    expect($regex)->toBe('\(\d{3}\) \d{3}\-\d{4}');
});

it('extracts dates in specific format from text using wrapper', function () {
    $matches = EloquentRegex::customPattern("Meeting on 2021-09-15 and 2021-10-20")
        ->digits(4)
        ->dash()
        ->digits(2)
        ->dash()
        ->digits(2)
        ->get();

    expect($matches)->toEqual(['2021-09-15', '2021-10-20']);
});

it('validates usernames in a string using wrapper and LengthOption', function () {
    $check = EloquentRegex::customPattern("Users: user_123, JohnDoe_99")
        ->alphanumeric()
        ->underscore()
        ->digitsRange(0, 2)
        ->end(["minLength" => 10])
        ->checkString();

    expect($check)->toBeTrue();
});

it('validates usernames in a string using wrapper and callback options', function () {
    $check = EloquentRegex::customPattern("Users: user_123, JohnDoe_99")
        ->alphanumeric()
        ->underscore()
        ->digits()
        ->end(function ($p) {
            $p->minLength(10)->maxDigits(2);
        })
        ->checkString();

    expect($check)->toBeTrue();
});

it('extracts hashtags from text using wrapper', function () {
    $matches = EloquentRegex::start("#hello #world This is a #test")
        ->hash()
        ->text()
        ->get();

    expect($matches)->toEqual(['#hello', '#world', '#test']);
});

it('extracts secret coded messages from text using wrapper', function () {
    $text = "Normal text {secret: message one} more text {secret: another hidden text} end";
    $matches = EloquentRegex::start($text)
        ->lookBehind(function ($pattern) {
            $pattern->openCurlyBrace()->exact('secret: ');
        })
        ->lazy()->anyChars()
        ->lookAhead(function ($pattern) {
            $pattern->closeCurlyBrace();
        })
        ->get();

    expect($matches)->toEqual(['message one', 'another hidden text']);
});

// Ready-to-use pattern tests:

// TextOrNumbersPattern
it('validates text with numbers correctly', function () {
    $builder = EloquentRegex::source("Text123");

    $check = $builder->textOrNumbers()->check();

    expect($check)->toBeTrue();
});

// EmailPattern
it('validates an email address correctly', function () {
    $builder = EloquentRegex::source("test@example.com");

    $check = $builder->email()->check();

    expect($check)->toBeTrue();
});

// DomainNamePattern
it('validates a domain name correctly', function () {
    $builder = EloquentRegex::string("example.com");

    $check = $builder->domainName()->check();

    expect($check)->toBeTrue();
});

// DatePattern
it('validates a date format correctly', function () {
    $builder = EloquentRegex::string("2023-01-01");

    $check = $builder->date()->check();

    expect($check)->toBeTrue();
});

// TimePattern
it('validates a time format correctly', function () {
    $builder = EloquentRegex::string("23:59");

    $check = $builder->time('H:i')->check();

    expect($check)->toBeTrue();
});

// IPAddressPattern
it('validates an IPv4 address correctly', function () {
    $builder = EloquentRegex::string("192.168.1.1");

    $check = $builder->ipAddress()->check();

    expect($check)->toBeTrue();
});

// IPv6AddressPattern
it('validates an IPv6 address correctly', function () {
    $builder = EloquentRegex::string("2001:0db8:85a3:0000:0000:8a2e:0370:7334");

    $check = $builder->ipv6Address()->check();

    expect($check)->toBeTrue();
});

// CreditCardNumberPattern
it('validates a credit card number correctly', function () {
    $builder = EloquentRegex::source("4111111111111111"); // A common Visa test number

    $check = $builder->creditCardNumber()->check();

    expect($check)->toBeTrue();
});

// PhonePattern
it('validates a phone number correctly', function () {
    $builder = EloquentRegex::string("+1 (123) 456-7890");

    $check = $builder->phone()->check();

    expect($check)->toBeTrue();
});

// UsernamePattern
it('validates a username correctly', function () {
    $builder = EloquentRegex::string("user_123");

    $check = $builder->username()->check();

    expect($check)->toBeTrue();
});

// HtmlTagPattern
it('identifies HTML content correctly', function () {
    $builder = EloquentRegex::source("<div>example</div>");

    $check = $builder->htmlTag()->check();

    expect($check)->toBeTrue();
});

// CurrencyPattern
it('validates currency format correctly', function () {
    $builder = EloquentRegex::string("$100.00");

    $check = $builder->currency()->check();

    expect($check)->toBeTrue();
});

// FilePathPattern
it('validates a Unix file path correctly', function () {
    $string = "/user/directory/file.txt";
    $builder = EloquentRegex::string($string);

    $check = $builder->filePath([
        "isDirectory" => false,
        "isFile" => "txt",
    ])->check();

    expect($check)->toBeTrue();
});


// Quantifier tests:
it('matches specific number of dashes', function () {
    $result = EloquentRegex::builder()->pattern()->dash('?')->toRegex();
    expect($result)->toBe('(?:\-)?');
});

it('matches optional dots', function () {
    $result = EloquentRegex::builder()->pattern()->dot('?')->toRegex();
    expect($result)->toBe('(?:\.)?');
});

it('matches multiple spaces', function () {
    $result = EloquentRegex::builder()->pattern()->space('2,5')->toRegex();
    expect($result)->toBe('(?: ){2,5}');
});

it('matches one or more backslashes', function () {
    $result = EloquentRegex::start("\\\\\\")->backslash('+')->check();
    expect($result)->toBe(true);
});

it('matches zero or more forward slashes', function () {
    $result = EloquentRegex::builder()->start()->forwardSlash('*')->toRegex();
    expect($result)->toBe('(?:\/)*');
});

it('matches exactly 4 underscores', function () {
    $result = EloquentRegex::builder()->start()->underscore('4')->toRegex();
    expect($result)->toBe('(?:_){4}');
});

it('matches one or more pipes', function () {
    $result = EloquentRegex::builder()->start()->pipe('+')->toRegex();
    expect($result)->toBe('(?:\|)+');
});

it('matches a specific number of character sets', function () {
    $regex = EloquentRegex::builder()->start()
        ->charSet(function ($pattern) {
            $pattern->period()->colon();
        }, '3')->toRegex();

    expect($regex)->toBe('(?:[\.\:]){3}');
});

it('matches a specific number of negative character sets', function () {
    $regex = EloquentRegex::builder()->start()
        ->negativeCharSet(function ($pattern) {
            // "digits" and similar classes adds quantifier+ automaticaly
            // Inside set "+" is parsed as symbol, instead of quantifier
            // So, inside charSet and negativeCharSet method, you should
            // pass 0 as first argument to do not apply quantifier here
            $pattern->digits();
        }, '2,4')->toRegex();

    expect($regex)->toBe('(?:[^\d]){2,4}');
});

it('matches a specific number of negative character sets using text method', function () {
    $regex = EloquentRegex::builder()->start()
        ->negativeCharSet(function ($pattern) {
            $pattern->text();
        }, '2,4')->toRegex();

    expect($regex)->toBe('(?:[^a-zA-Z]){2,4}');
});

it('applies quantifier to capturing groups correctly', function () {
    $regex = EloquentRegex::builder()->start()
        ->group(function ($pattern) {
            $pattern->text();
        }, '+')->toRegex();

    expect($regex)->toBe('(?:([a-zA-Z]+))+');
});

it('applies quantifier to non-capturing groups correctly', function () {
    $regex = EloquentRegex::builder()->start()
        ->nonCapturingGroup(function ($pattern) {
            $pattern->digits();
        }, '*')->toRegex();

    expect($regex)->toBe('(?:(?:\d+))*');

    $res = EloquentRegex::start("345-45, 125-787, 344643")
    ->nonCapturingGroup(function ($pattern) {
        $pattern->digits()->dash()->digits();
    }, '+') // Using "+" to match One Or More of this group
    ->get();

    expect($res)->toBe([
        "345-45",
        "125-787"
    ]);
});

test('group method creates capturing groups correctly', function () {
    // Matching a date format across multiple lines without capturing the groups
    $result = EloquentRegex::start("2024-01-30, 2023-02-20")
    ->group(function($pattern) {
        $pattern->digits(4); // Year
    })->dash()
    ->group(function($pattern) {
        $pattern->digits(2); // Month
    })->dash()
    ->group(function($pattern) {
        $pattern->digits(2); // Day
    })->end(["excludeChars" => ["4"]])
    ->get();

    expect($result)->toBe([
        [
            "result" => "2023-02-20",
            "groups" => [
                "2023",
                "02",
                "20"
            ],
        ]
    ]);
});

it('uses quantifier with alternation patterns correctly', function () {
    $regex = EloquentRegex::builder()->start()
        ->group(function ($pattern) {
            $pattern->text()->orPattern(function ($pattern) {
                $pattern->digits();
            }, "?");
        })->toRegex();

    expect($regex)->toBe('([a-zA-Z]+|(?:\d+)?)');
});

// Regex flags tests:

it('uses asCaseInsensitive method to match pattern correctly', function () {
    $checkWithFlag = EloquentRegex::source("EXAMPLE@Email.com")
        ->start()
        ->exact("example")
        ->character("@")
        ->exact("email.com")
        ->end()
        ->asCaseInsensitive()->check();

    expect($checkWithFlag)->toBeTrue();
});


// Replace feature tests:


it('replaces all texts using replace method', function () {
    $replaced = EloquentRegex::source("Send to example@email.com or replay to EXAMPLE2@Email.com")
        ->email()
        ->replace(function($foundString) {
            return "Email: " . $foundString;
        });

    expect($replaced)->toBe("Send to Email: example@email.com or replay to Email: EXAMPLE2@Email.com");
});


it('replaces the same texts using replace method', function () {
    $replaced = EloquentRegex::source("Send to example@email.com or replay to example@email.com")
        ->email()
        ->replace(function($foundString) {
            return "Email: " . $foundString;
        });

    expect($replaced)->toBe("Send to Email: example@email.com or replay to Email: example@email.com");
});


it('accepts PHP functions in replace method', function () {
    $replaced = EloquentRegex::source("Send to example-1@email.com or replay to example-2@email.com")
        ->email()
        ->replace(function($foundString) {
            return strToUpper($foundString);
        });

    expect($replaced)->toBe("Send to EXAMPLE-1@EMAIL.COM or replay to EXAMPLE-2@EMAIL.COM");
});


it('Replaces hashtags with anchors', function () {
    $replaced = EloquentRegex::start("This is a #test to wrap hashtags in #anchor tag")
    ->hash()->text()
    ->replace(function($foundItem) {
        return "<a href='$foundItem'>" . $foundItem . "</a>";
    });

    expect($replaced)->toBe("This is a <a href='#test'>#test</a> to wrap hashtags in <a href='#anchor'>#anchor</a> tag");
});

// Search feature tests:

it('searches multiline string using keyword', function () {
    $found = EloquentRegex::source(
        "
        Whose woods these are I think I know.\n
        His house is in the village though;\n
        He will not see me stopping here\n
        To watch his woods fill up with snow.\n
        \n
        The woods are lovely, dark and deep,\n
        But I have promises to keep,\n
        And miles to go before I sleep,\n
        And miles to go before I sleep.\n
        "
        )
        ->search("woods");

    expect($found)->toBe([
        "Whose woods these are I think I know.",
        "To watch his woods fill up with snow.",
        "The woods are lovely, dark and deep,",
    ]);
});

it('searches multiline string using subpattern with ready-to-use patterns', function () {
    $found = EloquentRegex::source(
        "
        Please contact us via email at info@example.com for more details.
        For support inquiries, you can also email us at support@example.com.
        Our marketing team is reachable at marketing@example.com for collaborations.
        For urgent matters, you can reach out through the phone number provided.
        Subscribe to our newsletter to stay updated with the latest news.
        Feel free to send feedback directly to our office address.
        Any emails sent after 5 PM may be responded to the next business day.
        Check the FAQ section for answers to common questions.
        Social media channels are also available for quick updates.
        We value your input and encourage you to share your thoughts.
        "
        )
        ->search(function ($pattern) {
            $pattern->email();
        });

    expect($found)->toBe([
        'Please contact us via email at info@example.com for more details.',
        'For support inquiries, you can also email us at support@example.com.',
        'Our marketing team is reachable at marketing@example.com for collaborations.'
    ]);
});

it('searches multiline string using subpattern with builder pattern methods', function () {
    $found = EloquentRegex::source(
        "
        Discover the latest tips and tricks to boost your productivity.
        Join the conversation with #LaravelTips and #WebDevelopment.
        Stay updated with our blog for more insightful content.
        Follow us on social media and use #CodingMadeEasy to share your journey.
        Let’s build something amazing together!
        "
        )
        ->search(function ($pattern) {
            $pattern->start()->hashtag()->alphanumeric();
        });

    expect($found)->toBe([
        'Join the conversation with #LaravelTips and #WebDevelopment.',
        'Follow us on social media and use #CodingMadeEasy to share your journey.',
    ]);
});



// SearchReverse feature tests:

it('Excepts lines from multiline string using keyword', function () {
    // Find all logs except INFO type
    $found = EloquentRegex::source(
        "
        [2024-12-23 10:00:00] INFO: User logged in.\n
        [2024-12-25 10:05:00] ERROR: Unable to connect to database.\n
        [2024-12-25 10:10:00] INFO: User updated profile.\n
        [2024-12-15 10:15:00] WARNING: Disk space running low.\n
        [2024-12-34 10:20:00] ERROR: Timeout while fetching data.\n
        "
        )
        ->searchReverse("INFO");

    expect($found)->toBe([
        '[2024-12-25 10:05:00] ERROR: Unable to connect to database.',
        '[2024-12-15 10:15:00] WARNING: Disk space running low.',
        '[2024-12-34 10:20:00] ERROR: Timeout while fetching data.',
    ]);
});



it('Excepts lines from multiline string using subpattern', function () {
    // Find all logs that don't happened in 25 december
    $found = EloquentRegex::source(
        "
        [2024-12-23 10:00:00] INFO: User logged in.\n
        [2024-12-25 10:05:00] ERROR: Unable to connect to database.\n
        [2024-12-25 10:10:00] INFO: User updated profile.\n
        [2024-12-15 10:15:00] WARNING: Disk space running low.\n
        [2024-12-34 10:20:00] ERROR: Timeout while fetching data.\n
        "
        )
        ->searchReverse(function ($pattern) {
            $pattern->start()->numbers()->dash()->numbers()->dash()->exact("25");
        });

    expect($found)->toBe([
        '[2024-12-23 10:00:00] INFO: User logged in.',
        '[2024-12-15 10:15:00] WARNING: Disk space running low.',
        '[2024-12-34 10:20:00] ERROR: Timeout while fetching data.',
    ]);
});


// Groups tests:
it('returns group data correctly (JIRA issue IDs)', function () {
    $found = EloquentRegex::start("RI-2142, PO-2555")
        ->group(function ($pattern) {
            return $pattern->textUppercase(2);
        }, 1)
        ->dash()
        ->group(function ($pattern) {
            return $pattern->digitsRange(2, 4);
        }, 1)->get();

    expect($found)->toBe([
        [
            "result" => "RI-2142",
            "groups" => [
                "RI",
                "2142"
            ]
        ],
        [
            "result" => "PO-2555",
            "groups" => [
                "PO",
                "2555"
            ]
        ]
    ]);
});


// Named groups tests:
it('returns named groups data correctly (JIRA issue IDs)', function () {
    // Parse JIRA issue IDs
    $found = EloquentRegex::start("RI-2142, PO-2555")
        ->namedGroup(function ($pattern) {
            return $pattern->textUppercase(2);
        }, "project", 1)
        ->dash()
        ->namedGroup(function ($pattern) {
            return $pattern->digitsRange(2, 4);
        }, "issue", 1)->get();

    expect($found)->toBe([
        [
            "result" => "RI-2142",
            "groups" => [
                "project" => "RI",
                "issue" => "2142",
            ]
        ],
        [
            "result" => "PO-2555",
            "groups" => [
                "project" => "PO",
                "issue" => "2555",
            ]
        ]
    ]);
});

// Swap tests:
it('can swap text using callback', function () {
    $builder = EloquentRegex::start("RI-2142, RI-1234, PO-2555");
    $result = $builder
        ->namedGroup(function ($pattern) {
            return $pattern->textUppercase(2);
        }, "project", 1)
        ->dash()
        ->namedGroup(function ($pattern) {
            return $pattern->digitsRange(2, 4);
        }, "issue", 1)
        ->end();

    $results = $result->swap(function ($data) {
        return "The issue #" . $data["issue"] . " of project " . $data["project"] ." is in progress";
    });

    expect($results)->toBe([
        'The issue #2142 of project RI is in progress',
        'The issue #1234 of project RI is in progress',
        'The issue #2555 of project PO is in progress'
    ]);
});

it('can swap text using pattern string', function () {
    $builder = EloquentRegex::start("/container-tbilisi-1585, /container-berlin-1234, /container-tbilisi-2555");
    $result = $builder
        ->slash()
        ->exact("container")
        ->dash()
        ->namedGroup(function ($pattern) {
            return $pattern->text();
        }, "City")
        ->dash()
        ->namedGroup(function ($pattern) {
            return $pattern->digitsRange(2, 5);
        }, "id")
        ->end();

    $results = $result->swap("/container/[ID]?city=[CITY]");

    expect($results)->toBe([
        '/container/1585?city=tbilisi',
        '/container/1234?city=berlin',
        '/container/2555?city=tbilisi'
    ]);
});
