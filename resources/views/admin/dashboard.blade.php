@extends('layout.admin')


@section('title')
    الرئيسية
@endsection

@section('content')
    <div style="width:100%; height:74vh;">
        <img style="width:85%; height: 90%;" src="{{ asset('assets\admin\images\bg.png') }}" alt="">
    </div>
@endsection

@section('contentheader')
    الرئيسية
@endsection

@section('contentheaderlink')
    <a href="#">الرئيسية</a>
@endsection

@section('contentheaderactive')
    عرض
@endsection
