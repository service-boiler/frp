@extends('layouts.app')

@section('header')
<header style="padding-top: 60px;">

</header>
@endsection

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-3 col-sm-6 portfolio-item">
                <div class="card h-100">
                    <a href="///service.kotelkotel.ru{{env('TEST_SITE')==1 ? '.test' : ''}}">
                        <img class="card-img-top" src="/storage/catalogs/srv-400.png" alt="">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <a href="///service.kotelkotel.ru{{env('TEST_SITE')==1 ? '.test' : ''}}">Котельный сервис. Личный кабинет</a>
                        </h5>

                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 portfolio-item">
                <div class="card h-100">
                    <a href="///zip.kotelkotel.ru{{env('TEST_SITE')==1 ? '.test' : ''}}">
                        <img class="card-img-top" src="/storage/catalogs/zip-400.png" alt="">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <a href="///zip.kotelkotel.ru{{env('TEST_SITE')==1 ? '.test' : ''}}">Запчасти для котлов</a>
                        </h5>

                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 portfolio-item">
                <div class="card h-100">
                    <a href="///docs.kotelkotel.ru{{env('TEST_SITE')==1 ? '.test' : ''}}">
                        <img class="card-img-top" src="/storage/catalogs/docs-400.png" alt="">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <a href="///docs.kotelkotel.ru{{env('TEST_SITE')==1 ? '.test' : ''}}">Документация</a>
                        </h5>

                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 portfolio-item">
                <div class="card h-100">
                    <a href="///market.kotelkotel.ru{{env('TEST_SITE')==1 ? '.test' : ''}}">
                        <img class="card-img-top" src="/storage/catalogs/market-400.png" alt="">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <a href="///market.kotelkotel.ru{{env('TEST_SITE')==1 ? '.test' : ''}}">Интернет-магазин <br />котлов отопления</a>
                        </h5>

                    </div>
                </div>
            </div>





        </div>
    
    
    </div>

    
@endsection
