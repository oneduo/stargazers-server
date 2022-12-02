<?php

namespace App\GraphQL\Subscriptions;

use Illuminate\Http\Request;
use Nuwave\Lighthouse\Schema\Types\GraphQLSubscription;
use Nuwave\Lighthouse\Subscriptions\Subscriber;

class PackageUpdated extends GraphQLSubscription
{
    public function authorize(Subscriber $subscriber, Request $request): bool
    {
        throw new \Exception('try to authorize subscription');
    }

    public function filter(Subscriber $subscriber, $root): bool
    {
        $stargazer = $subscriber->context->request()->cookie(config('app.cookie_name'));

        return (int) $stargazer === (int) $root->pivot?->stargazer_id;
    }

    public function encodeTopic(Subscriber $subscriber, string $fieldName): string
    {
        return str($fieldName)->snake().':'.$subscriber->args['stargazer'];
    }

    /**
     * @param  string  $fieldName
     * @param  \App\Models\Package  $root
     * @return string
     */
    public function decodeTopic(string $fieldName, $root): string
    {
        $stargazer_id = $root->pivot?->stargazer_id;

        return str($fieldName)->snake().':'.$stargazer_id;
    }
}
