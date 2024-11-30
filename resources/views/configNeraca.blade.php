@extends('layouts.layout')
@extends('layouts.sidebar')

@php
$role = Auth::user()->role;
$role = explode(', ', $role);
@endphp

@section('title')
Konfigurasi Neraca
@endsection

@section('configNeracaStatus')
active
@endsection

@section('css')
<link href="{{asset('css/dragula.min.css')}}" rel="stylesheet" />
<style>
    .dd-container {
        min-height: 3em;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <form class="row" method="POST" action="{{ route('configneraca.update') }}">
                @csrf
                <div class="toolbar col-12 text-right mt-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="material-icons">save</i> Simpan</button>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <h4 class="card-title">Modal</h4>
                        <div class="card-body dd-container2" data-type="modal">
                            @foreach($left as $d)
                            <div class="card category">
                                <h4 class="card-title">{{$d['name']}}</h4>
                                <input type="hidden" value="{{$d['id']}}" name="category[modal][]"/>
                                <ul class="list-group list-group-flush dd-container" data-id="{{$d['id']}}">
                                    @foreach($d['data'] as $c)
                                    <li class="list-group-item" data-id="{{$c['id']}}">{{$c['name']}}
                                        <input type="hidden" value="{{$c['id']}}" name="akun[{{$d['id']}}][]"/>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                            @foreach($orphans as $c)
                            <li class="list-group-item" data-id="{{$c['id']}}">{{$c['name']}}
                                <input type="hidden" value="{{$c['id']}}" name="akun[orphan][]"/>
                            </li>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <h4 class="card-title">Beban</h4>
                        <div class="card-body dd-container2" data-type="beban">
                            @foreach($right as $d)
                            <div class="card category">
                                <h4 class="card-title">{{$d['name']}}</h4>
                                <input type="hidden" value="{{$d['id']}}" name="category[beban][]"/>
                                <ul class="list-group list-group-flush dd-container" data-id="{{$d['id']}}">
                                    @foreach($d['data'] as $c)
                                    <li class="list-group-item" data-id="{{$c['id']}}">{{$c['name']}}
                                        <input type="hidden" value="{{$c['id']}}" name="akun[{{$d['id']}}][]"/>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('js/plugins/dragula.min.js')}}"></script>
<script>
var containers;
$(document).ready(function() {
    containers = $('.dd-container').toArray();
    var myConfig = dragula(containers, {
        isContainer: function (el) {
            return el.classList.contains('dd-container2');
        },
        accepts: function (el, target, source, sibling) {
            if (
                el.classList.contains('category') &&
                target.classList.contains('dd-container')
            ){
                return false;
            }
            return true;
        },
    });
    myConfig.on('drop', function (el, target, source, sibling) {
        let input = el.querySelector('input');
        if (el.classList.contains('list-group-item')) {
            if (target.classList.contains('dd-container2')) {
                input.name = "akun[orphan][]";
            } else {
                input.name = "akun[" + target.dataset.id + "][]";
            }
        } else {
            if (target.classList.contains('dd-container2')) {
                input.name = "category[" + target.dataset.type + "][]";
            }
        }
    })
});
</script>
@endsection