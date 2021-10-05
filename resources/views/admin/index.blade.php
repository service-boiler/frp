@extends('layouts.app')
</div>
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.admin')</li>
        </ol>
        <h1 class="header-title mb-4"><i class="fa fa-sliders"></i> @lang('site::messages.admin')</h1>
        @alert()@endalert
        <div class="row mb-4">

            <div class="col-md-4">

                <!-- Tasks -->
                <div class="card mb-4">
                    <h6 class="card-header">@lang('site::messages.admin')</h6>
                    <div class="list-group">


                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.orders.index') }}"><i
                                    class="fa fa-@lang('site::order.icon')"></i> @lang('site::order.orders') (Заявки ТОРГ)</a>


                        <a class="list-group-item list-group-item-action py-1"
                           href="{{ route('admin.engineers.index') }}">
                            <i class="fa fa-@lang('site::engineer.icon')"></i> @lang('site::engineer.engineers')
                        </a>
                        <a class="list-group-item list-group-item-action py-1"
                           href="{{ route('admin.esb-clients.index') }}">
                            <i class="fa fa-dollar"></i> Клиенты - потребители
                        </a>
                        <a class="list-group-item list-group-item-action py-1"
                           href="{{ route('esb-user-products.index') }}">
                            <i class="fa fa-cogs"></i> Оборудование клиентов
                        </a>
                        <a class="list-group-item list-group-item-action py-1"
                           href="{{ route('esb-product-maintenances.index') }}">
                            <i class="fa fa-clone"></i> Акты ТО
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('esb-contracts.index') }}">
                            <i class="fa fa-@lang('site::contract.icon')"></i> Договоры с клиентами
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('esb-contract-templates.index') }}">
                            <i class="fa fa-file-code-o"></i> Шаблоны договоров
                        </a>

                        <a class="list-group-item list-group-item-action py-1"
                           href="{{ route('contragents.index') }}">
                            <i class="fa fa-users"></i> Мои юр.лица
                        </a>
                        <a class="list-group-item list-group-item-action py-1"
                           href="{{ route('admin.contragents.index') }}">
                            <i class="fa fa-@lang('site::contragent.icon')"></i> Юр.лица партнеров и клиентов
                        </a>

                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.contracts.index') }}">
                            <i class="fa fa-@lang('site::contract.icon')"></i> Договоры с исполнителями
                        </a>

                        <hr/>
                        <hr/>
                    <!-- <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.dashboards.index') }}">
                            <i class="fa fa-dashboard"></i> @lang('site::messages.dashboards')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.tickets.index') }}">
                            <i class="fa fa-life-ring"></i> @lang('site::ticket.tickets')
                        </a>
                        -->
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.users.index') }}">
                            <i class="fa fa-user-o"></i> @lang('site::user.users')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.repairs.index') }}"><i
                                    class="fa fa-@lang('site::repair.icon')"></i> @lang('site::repair.repairs')</a>
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.acts.index') }}"><i
                                    class="fa fa-@lang('site::act.icon')"></i> @lang('site::act.acts')</a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.authorizations.index') }}"><i
                                    class="fa fa-@lang('site::authorization.icon')"></i> @lang('site::authorization.authorizations')
                        </a>
                        <hr/> 
                        
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.messages.index') }}"><i
                                    class="fa fa-@lang('site::message.icon')"></i> @lang('site::message.messages')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.mailings.create') }}">
                                    <i class="fa fa-@lang('site::mailing.icon')"></i> @lang('site::messages.create') @lang('site::mailing.mailing')
                        </a>
                        <hr/> 


                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.catalogs.index') }}">
                            <i class="fa fa-@lang('site::catalog.icon')"></i> @lang('site::catalog.catalogs')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.equipments.index') }}">
                            <i class="fa fa-@lang('site::equipment.icon')"></i> @lang('site::equipment.equipments')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.equipments.market-sort') }}">
                            <i class="fa fa-@lang('site::equipment.icon')"></i> @lang('site::equipment.equipments_sort_menu')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.products.index') }}">
                            <i class="fa fa-@lang('site::product.icon')"></i> @lang('site::product.cards')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.datasheets.index') }}">
                            <i class="fa fa-@lang('site::datasheet.icon')"></i> @lang('site::datasheet.datasheets')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.revision_parts.index') }}">
                            <i class="fa fa-info"></i> @lang('site::admin.revision_part.index')
                        </a>

                        <hr/>


                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.announcements.index') }}">
                            <i class="fa fa-@lang('site::announcement.icon')"></i> @lang('site::announcement.announcements')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.webinars.index') }}">
                            <i class="fa fa-video-camera"></i> @lang('site::admin.webinar.index')
                        </a>

					<hr/>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.video_blocks.index') }}">
                            <i class="fa fa-@lang('site::admin.video_icon')"></i> @lang('site::admin.video_blocks_index')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="/admin/head_banner_blocks">
                            <i class="fa fa-image"></i> @lang('site::admin.head_banner_blocks_index')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="/admin/index_cards_blocks">
                            <i class="fa fa-id-badge"></i> @lang('site::admin.index_cards_blocks')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="/admin/index_quadro_blocks">
                            <i class="fa fa-image"></i> @lang('site::admin.index_quadro_blocks_index')
                        </a>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.variables.index') }}">
                                <i class="fa fa-cog"></i> @lang('site::admin.variables')
                            </a>

                            <hr/>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.currencies.index') }}">
                                <i class="fa fa-@lang('site::currency.icon')"></i> @lang('site::currency.currencies')
                            </a>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.currency_archives.index') }}">
                                <i class="fa fa-@lang('site::archive.icon')"></i> @lang('site::archive.archives')
                            </a>

                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.banks.index') }}">
                                <i class="fa fa-@lang('site::bank.icon')"></i> @lang('site::bank.banks')
                            </a>

                            <hr/>

                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.contract-types.index') }}">
                                <i class="fa fa-@lang('site::contract_type.icon')"></i> @lang('site::contract_type.contract_types')
                            </a>

                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.productspecs.index') }}">
                                <i class="fa fa-cogs"></i> Тех.хар.товаров
                            </a>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.blocks.index') }}">
                                <i class="fa fa-@lang('site::block.icon')"></i> @lang('site::block.blocks')
                            </a>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.schemes.index') }}">
                                <i class="fa fa-@lang('site::scheme.icon')"></i> @lang('site::scheme.schemes')
                            </a>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.elements.index') }}">
                                <i class="fa fa-@lang('site::element.icon')"></i> @lang('site::element.elements')
                            </a>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.reviews.index') }}">
                                <i class="fa fa-star"></i> @lang('site::admin.review.reviews')
                            </a>

                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.black-list.index') }}">
                                <i class="fa fa-thumbs-down"></i> @lang('site::admin.black_list.black_list')
                            </a>

                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.addresses.index') }}">
                                <i class="fa fa-@lang('site::address.icon')"></i> @lang('site::address.addresses')
                            </a>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.contacts.index') }}">
                                <i class="fa fa-@lang('site::contact.icon')"></i> @lang('site::contact.contacts')
                            </a>

                            <hr/>

                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.serials.index') }}">
                                <i class="fa fa-@lang('site::serial.icon')"></i> @lang('site::serial.serials')
                            </a>

                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.certificates.index') }}">
                                <i class="fa fa-@lang('site::certificate.icon')"></i> @lang('site::certificate.certificates')
                            </a>

                            <hr/>

                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.price_types.index') }}">
                                <i class="fa fa-@lang('site::price_type.icon')"></i> @lang('site::price_type.price_types')
                            </a>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.product_types.index') }}">
                                <i class="fa fa-@lang('site::product_type.icon')"></i> @lang('site::product_type.product_types')
                            </a>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.file_groups.index') }}">
                                <i class="fa fa-@lang('site::file_group.icon')"></i> @lang('site::file_group.file_groups')
                            </a>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.file_types.index') }}">
                                <i class="fa fa-@lang('site::file_type.icon')"></i> @lang('site::file_type.file_types')
                            </a>

                            <hr/>

                            <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.roles.index') }}">
                                <i class="fa fa-graduation-cap"></i> @lang('rbac::role.roles')
                            </a>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.permissions.index') }}">
                                <i class="fa fa-unlock-alt"></i> @lang('rbac::permission.permissions')
                            </a>

                            <hr/>

                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.authorization-roles.index') }}">
                                <i class="fa fa-@lang('site::authorization_role.icon')"></i> @lang('site::authorization_role.authorization_roles')
                            </a>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.authorization-types.index') }}">
                                <i class="fa fa-@lang('site::authorization_type.icon')"></i> @lang('site::authorization_type.authorization_types')
                            </a>
                            <a class="list-group-item list-group-item-action py-1"
                               href="{{ route('admin.authorization-brands.index') }}">
                                <i class="fa fa-@lang('site::authorization_brand.icon')"></i> @lang('site::authorization_brand.authorization_brands')
                            </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
