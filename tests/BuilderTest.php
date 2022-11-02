<?php

use Illuminate\Support\Facades\DB;
use Spatie\JsonApiPaginate\Test\TestModel;

it('can paginate records')
    ->expect(fn () => TestModel::jsonPaginate())->nextPageUrl()
    ->toEqual('http://localhost?page%5Bnumber%5D=2');

it('can paginate records with cursor')
    ->tap(fn () => config()->set('json-api-paginate.use_cursor_pagination', true))
    ->expect(fn () => TestModel::jsonPaginate()->nextPageUrl())
    ->toEqual('http://localhost?page%5Bcursor%5D=eyJ0ZXN0X21vZGVscy5pZCI6MzAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0');

it('returns the amount of records specified in the config file')
    ->tap(fn () => config()->set('json-api-paginate.default_size', 10))
    ->expect(fn () => TestModel::jsonPaginate())
    ->toHaveCount(10);

it('returns the amount of records specified in the config file using cursor')
    ->tap(fn () => config()->set('json-api-paginate.use_cursor_pagination', true))
    ->tap(fn () => config()->set('json-api-paginate.default_size', 10))
    ->expect(fn () => TestModel::jsonPaginate())
    ->toHaveCount(10);

it('can return the specified amount of records')
    ->expect(fn () => TestModel::jsonPaginate(15))
    ->toHaveCount(15);

it('can return the specified amount of records with cursor')
    ->tap(fn () => config()->set('json-api-paginate.use_cursor_pagination', true))
    ->expect(fn () => TestModel::jsonPaginate(15))
    ->toHaveCount(15);

it('will not return more records that the configured maximum')
    ->expect(fn () => TestModel::jsonPaginate(15))
    ->toHaveCount(15);

it('can set a custom base url in the config file')
    ->tap(fn () => config()->set('json-api-paginate.base_url', 'https://example.com'))
    ->expect(fn () => TestModel::jsonPaginate()->nextPageUrl())
    ->toEqual('https://example.com?page%5Bnumber%5D=2');

it('can use simple pagination')
    ->tap(fn () => config()->set('json-api-paginate.use_simple_pagination', true))
    ->expect(
        fn () => method_exists(TestModel::jsonPaginate(), 'total')
    )->toBeFalse();

it('can use cursor pagination')
    ->tap(fn () => config()->set('json-api-paginate.use_cursor_pagination', true))
    ->expect(
        fn () => method_exists(TestModel::jsonPaginate(), 'total')
    )->toBeFalse();

it('can use base query builder')
    ->expect(fn () => DB::table('test_models')->jsonPaginate()->nextPageUrl())
    ->toEqual('http://localhost?page%5Bnumber%5D=2');

it('can use base query builder with custom pagination')
    ->tap(fn () => config()->set('json-api-paginate.use_cursor_pagination', true))
    ->expect(fn () => DB::table('test_models')->orderBy('id')->jsonPaginate(10))->nextPageUrl()
    ->toEqual('http://localhost?page%5Bcursor%5D=eyJpZCI6MTAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0');
