<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
Route::rule('/','api/index/index');
Route::group('api',[
    'Login'  => ['api/Login/login'],//登陆
    'index'  => ['api/Index/index'],//首页
//    'menu_all'  => ['api/Index/menu_all'],//全部菜单
    'demo'  => ['api/Index/demo'],//测试
    'demo_count'  => ['api/Index/demo_count'],//测试
    'demo_slot'  => ['api/Index/demo_slot'],//测试
    'menu'  => ['api/Index/menu'],//菜单
    'theme'  => ['api/Index/theme'],//主题
    'time_slot'  => ['api/Index/time_slot'],//主题时间段
    'sms'  => ['api/Sms/sms'],//自动发送短信
    'name_integral'  => ['api/User/name_integral'], //客户积分数据
    'investigation_select'  => ['api/User/investigation_select'], //客户游戏数据
    'investigation_detail'  => ['api/User/investigation_detail'], //客户游戏数据
    'reserve_add'  => ['api/Reserve/reserve_add'], //添加预约
    'reserve_edit'  => ['api/Reserve/reserve_edit'], //修改预约
    'reserve_delete'  => ['api/Reserve/reserve_delete'],  //删除预约
    'reserve_detail'  => ['api/Reserve/reserve_detail'],  //预约详情
    'money_day'  => ['api/Reserve/money_day'], //每日营业额
    'statistics'  => ['api/Statistics/statistics'],//总数据
    'theme_money'  => ['api/Statistics/theme_money'], //主题营业额
    'store_money'  => ['api/Statistics/store_money'],//门店营业额
    'over_time'  => ['api/Statistics/over_time'], //加班总额
    'over_staff'  => ['api/Statistics/over_staff'], //加班总额
    'customer'  => ['api/Statistics/customer'], //每个门店数据
    'count'  => ['api/Statistics/count'], //年龄段来源客户统计
    'total_field'  => ['api/Statistics/total_field'], //每个主题数据
    'slot_add'  => ['api/Slot/slot_add'],//新增时间段
    'slot_edit'  => ['api/Slot/slot_edit'],  //时间段修改
    'slot_delete'  => ['api/Slot/slot_delete'],  //时间段删除
    'slot_select'  => ['api/Slot/slot_select'],  //时间段列表
    'slot_detail'  => ['api/Slot/slot_detail'],  //时间段详情
    'staff_add'  => ['api/Staff/staff_add'], //员工添加
    'staff_select'  => ['api/Staff/staff_select'],//员工列表
    'staff_delete'  => ['api/Staff/staff_delete'],//员工删除
    'staff_edit'  => ['api/Staff/staff_edit'],//修改员工
    'staff_detail'  => ['api/Staff/staff_detail'],//员工详情
    'wages_add'  => ['api/Staff/wages_add'],//新增员工工资条
    'wages_edit'  => ['api/Staff/wages_edit'], //修改员工工资条
    'wages_delete'  => ['api/Staff/wages_delete'], //删除员工工资条
    'wages_select'  => ['api/Staff/wages_select'],  //工资条查询
    'wages_detail'  => ['api/Staff/wages_detail'], //工资条详情
    'expenditure_add'  => ['api/Expenditure/expenditure_add'], //支出数据添加--营业费用
    'expenditure_edit'  => ['api/Expenditure/expenditure_edit'],//支出数据修改--营业费用
    'expenditure_delete'  => ['api/Expenditure/expenditure_delete'],//支出数据删除--营业费用
    'expenditure_count'  => ['api/Expenditure/expenditure_count'], //支出总计--按时间和门店ID
    'expenditure_select'  => ['api/Expenditure/expenditure_select'],//支出数据列表--营业费用
    'expenditure_detail'  => ['api/Expenditure/expenditure_detail'],//支出数据详情--营业费用
    'maintenance_add'  => ['api/Maintenance/maintenance_add'],//支出数据添加--密室维护费用
    'maintenance_edit'  => ['api/Maintenance/maintenance_edit'], //支出数据修改--密室维护费用
    'maintenance_delete'  => ['api/Maintenance/maintenance_delete'], //支出数据删除--密室维护费用
    'maintenance_select'  => ['api/Maintenance/maintenance_select'], //支出数据列表--密室维护费用
    'maintenance_detail'  => ['api/Maintenance/maintenance_detail'], //支出数据详情--密室维护费用
    'procurement_add'  => ['api/Procurement/procurement_add'],//支出数据添加--采购费用
    'procurement_edit'  => ['api/Procurement/procurement_edit'],//支出数据修改--采购费用
    'procurement_delete'  => ['api/Procurement/procurement_delete'],//支出数据删除--采购费用
    'procurement_select'  => ['api/Procurement/procurement_select'],//支出数据列表--采购费用
    'procurement_detail'  => ['api/Procurement/procurement_detail'],//支出数据详情--采购费用
    'marketing_add'  => ['api/Marketing/marketing_add'], //支出数据添加--营销费用
    'marketing_edit'  => ['api/Marketing/marketing_edit'], //支出数据修改--营销费用
    'marketing_delete'  => ['api/Marketing/marketing_delete'], //支出数据删除--营销费用
    'marketing_select'  => ['api/Marketing/marketing_select'], //支出数据列表--营销费用
    'marketing_detail'  => ['api/Marketing/marketing_detail'], //支出数据详情--营销费用
    'jurisdiction_select'  => ['api/Jurisdiction/jurisdiction_select'], //权限
    'jurisdiction_edit'  => ['api/Jurisdiction/jurisdiction_edit'], //权限
    'jurisdiction_add'  => ['api/Jurisdiction/jurisdiction_add'], //权限
    'jurisdiction_delete'  => ['api/Jurisdiction/jurisdiction_delete'], //权限
    'jurisdiction_detail'  => ['api/Jurisdiction/jurisdiction_detail'], //权限
    'good_add'  => ['api/Goods/good_add'], //物品新增
    'good_edit'  => ['api/Goods/good_edit'], //物品修改
    'good_delete'  => ['api/Goods/good_delete'], //物品删除
    'good_detail'  => ['api/Goods/good_detail'], //物品详情
    'good_select'  => ['api/Goods/good_select'], //物品列表
    'coupon'  => ['api/Coupon/coupon'], //物品列表
    'integral'  => ['api/Integral/integral'], //第二数据库
    'checkSignature'  => ['api/Integral/checkSignature'], //第二数据库
    'theme_add'  => ['api/Theme/theme_add'], //主题新增
    'theme_edit'  => ['api/Theme/theme_edit'], //主题修改
    'theme_delete'  => ['api/Theme/theme_delete'], //主题删除
    'theme_select'  => ['api/Theme/theme_select'], //主题列表
    'theme_detail'  => ['api/Theme/theme_detail'], //主题详情
    'store_add'  => ['api/Store/store_add'], //添加门店
    'store_edit'  => ['api/Store/store_edit'], //修改门店
    'store_delete'  => ['api/Store/store_delete'], //删除门店
    'store_select'  => ['api/Store/store_select'], //门店查询
    'store_detail'  => ['api/Store/store_detail'], //门店详情
    'write'  => ['api/Order/write'], //核销
    'over_work'  => ['api/Over/over_work'], //加班列表
    'over_list'  => ['api/Over/over_list'], //核销
    'order_list'  => ['api/Order/order_list'], //订单详情
], ['method'=>'post|get']);
Route::group('admin',[
    'integral'  => ['admin/User/integral'], //修改产品库存
    'order'  => ['admin/User/order'], //
    'register'  => ['admin/User/register'], //
    'wx'  => ['admin/WxOpen/'], //
    'getBaseInfo'  => ['admin/Wx/getBaseInfo'], //
    'getUserOpenId'  => ['admin/Wx/getUserOpenId'], //
    'getUserDetail'  => ['admin/Wx/getUserDetail'], //
    'getUserInfo'  => ['admin/Wx/getUserInfo'], //
], ['method'=>'post|get']);
