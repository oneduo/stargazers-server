<?php

namespace App\GraphQL\Subscriptions;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Schema\Types\GraphQLSubscription;
use Nuwave\Lighthouse\Subscriptions\Subscriber;

final class PackageUpdated extends GraphQLSubscription
{
    public function authorize(Subscriber $subscriber, Request $request): bool
    {
        return (int)$request->input('state') === (int)$subscriber->args['stargazer'];
    }

    public function filter(Subscriber $subscriber, $root): bool
    {
        return (int)$subscriber->args['stargazer'] === (int)$root->pivot?->stargazer_id;
    }

    public function encodeTopic(Subscriber $subscriber, string $fieldName): string
    {
        return Str::snake($fieldName).':'.$subscriber->args['stargazer'];
    }

    /**
     * Decode topic name.
     *
     * @param  string  $fieldName
     * @param  \App\Models\Package  $root
     * @return string
     */
    public function decodeTopic(string $fieldName, $root): string
    {
        $stargazer_id = $root->pivot?->stargazer_id;

        return Str::snake($fieldName).':'.$stargazer_id;
    }
}
