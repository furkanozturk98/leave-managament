import LeaveForm from './components/Leave/LeaveForm';
import LeaveIndex from './components/Leave/LeaveIndex';
import LeaveAdminIndex from './components/Leave/LeaveAdminIndex';

export const routes = [
    {
        path: '/leaves/',
        name: 'leave.list',
        component: LeaveIndex
    },
    {
        path: '/leaves/create',
        name: 'create',
        component: LeaveForm
    },
    {
        path: '/leaves/:id/edit',
        name: 'edit',
        component: LeaveForm
    },
    {
        path: '/leaves/manager',
        name: 'leave.list.manager',
        component: LeaveAdminIndex
    }

];
