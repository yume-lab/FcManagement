<?php $this->assign('title', 'タイムカード'); ?>

<div class="row">
    <div class="box center col-md-6">
        <div class="alert alert-info">
            タイムカード
        </div>
        <?= $this->Flash->render() ?>

        <div class="box-inner">
            <div class="box-content">
                <div class="box-content">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Username</th>
                            <th>Date registered</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="accordion">
                            <td><a href="#">Muhammad Usman</a>
                                <ul class="nav nav-pills nav-stacked" style="display: none;">
                                    <li><a href="#">Child Menu 1</a></li>
                                    <li><a href="#">Child Menu 2</a></li>
                                </ul>

                            </td>
                            <td class="center">2012/01/01</td>
                            <td class="center">Member</td>
                            <td class="center">
                                <span class="label-success label label-default">Active</span>
                            </td>


                        </tr>
                        <tr>
                            <td>White Horse</td>
                            <td class="center">2012/02/01</td>
                            <td class="center">Staff</td>
                            <td class="center">
                                <span class="label-default label label-danger">Banned</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Sheikh Heera</td>
                            <td class="center">2012/02/01</td>
                            <td class="center">Admin</td>
                            <td class="center">
                                <span class="label-default label">Inactive</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Saruar</td>
                            <td class="center">2012/03/01</td>
                            <td class="center">Member</td>
                            <td class="center">
                                <span class="label-warning label label-default">Pending</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Sana Amrin</td>
                            <td class="center">2012/01/21</td>
                            <td class="center">Staff</td>
                            <td class="center">
                                <span class="label-success label label-default">Active</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>


                    <ul class="dashboard-list" style="text-align: left;">
                        <li class="accordion">
                            <a href="#">
                                Usman
                            </a>
                            <strong>Since:</strong> 17/05/2014
                            <strong>Status:</strong> <span class="label-success label label-default">Approved</span>
                            <ul class="nav nav-pills nav-stacked" style="display: none;">
                                <li><a href="#">Child Menu 1</a></li>
                                <li><a href="#">Child Menu 2</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>