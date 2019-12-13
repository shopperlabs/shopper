import React, { useEffect, useState } from "react";
import ReactDom from "react-dom";
import axios from "axios";
import { Table, Avatar } from "evergreen-ui";

import route from "../../utils/route";

export type User = {
  id: number;
  first_name: string;
  last_name: string;
  full_name: string;
  email: string;
  picture: string;
  is_superuser: boolean;
  roles: Array<any>;
  created_at: string;
  updated_at: string;
}

const UserManagement = () => {
  const [users, setUsers] = useState([]);
  const [type, setType] = useState('all');

  useEffect(() => {
    (async function loadUsers() {
      const {data: { data }} = await axios.get(route('shopper.api.users.managers'));
      setUsers(data);
    })();
  }, []);

  console.log(users);

  return (
    <div className="user-management-page">
      <div className="subheader sh-header grid__item" id="subheader">
        <div className="container container--fluid">
          <div className="subheader__main">
            <h3 className="subheader__title">Users</h3>
          </div>
          <div className="subheader__toolbar">
            <div className="actions-wrapper">
              <a href="/keen/preview/demo6/custom/users/add.html" className="btn btn-brand">
                <i className="la la-filter" />
                Filter
              </a>
            </div>
            <a href="/keen/preview/demo6/custom/users/add.html" className="btn btn-brand btn-bold">
              <i className="la la-plus" />
              Add User
            </a>
          </div>
        </div>
      </div>

      <div className="users-status">
        <ul className="status-list">
          <li className="status-list__item"><a className={type === 'all' ? 'active' : ''} onClick={() => setType('all')}>All</a></li>
          <li className="status-list__item"><a className={type === 'unverified' ? 'active' : ''} onClick={() => setType('unverified')}>Unverified</a></li>
          <li className="status-list__item"><a className={type === 'deleted' ? 'active' : ''} onClick={() => setType('deleted')}>Deleted</a></li>
        </ul>
      </div>

      <div className="portlet">
        <div className="portlet__body">
          {type === "all" &&
            <Table>
              <Table.Head>
                <Table.TextHeaderCell>Name</Table.TextHeaderCell>
                <Table.TextHeaderCell>Status</Table.TextHeaderCell>
                <Table.TextHeaderCell>Created At</Table.TextHeaderCell>
                <Table.TextHeaderCell />
              </Table.Head>
              <Table.VirtualBody height={240}>
                {users.map((user: User) => (
                  <Table.Row key={user.id}>
                    <Table.TextCell>
                      <div className="user-detail">
                        <Avatar
                          src={user.picture}
                          name={user.full_name}
                          size={40}
                        />
                        <div className="user-profile">
                          <span>{user.full_name}</span>
                          <span>{user.email}</span>
                        </div>
                      </div>
                    </Table.TextCell>
                    <Table.TextCell>{user.last_name}</Table.TextCell>
                    <Table.TextCell>{user.created_at}</Table.TextCell>
                    <Table.TextCell>
                      <a
                        href="#"
                        className="btn btn-icon btn-label btn-label-brand btn-bold btn-sm mr-2"
                        data-toggle="tooltip"
                        title=""
                        data-placement="top"
                        data-original-title="Edit User"
                      >
                        <i className="flaticon-edit" />
                      </a>
                      <a
                        href="#"
                        className="btn btn-icon btn-label btn-label-danger btn-bold btn-sm"
                        data-toggle="tooltip"
                        title=""
                        data-placement="top"
                        data-original-title="Delete User"
                      >
                        <i className="flaticon-delete" />
                      </a>
                    </Table.TextCell>
                  </Table.Row>
                ))}
              </Table.VirtualBody>
            </Table>}
        </div>
      </div>
    </div>
  );
}

if (document.getElementById("users-access-management")) {
  ReactDom.render(
    <UserManagement />,
    document.getElementById("users-access-management")
  );
}
