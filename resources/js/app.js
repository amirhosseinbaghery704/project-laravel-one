import './bootstrap';
import Swal from 'sweetalert2';
import { multipleSelect } from 'multiple-select-vanilla';
import 'multiple-select-vanilla/dist/styles/css/multiple-select.css';

multipleSelect('.multiple-select');

const message = document.querySelector('#message');
if (message) {
  Swal.fire({
    title: 'Success!',
    text: message.getAttribute('value'),
    icon: 'success',
    confirmButtonText: 'Ok'
  })
}

window.deleteItem = function (id, title) {
  Swal.fire({
    title: "Are you sure?",
    text: `You are deleteing \`${title}\`.`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
  }).then((result) => {
    if (result.isConfirmed) {
      const item = document.querySelector('#delete-item-' + id);
      item.submit();
    }
  });
};

window.restoreItem = function (id, title) {
  Swal.fire({
    title: "Are you sure?",
    text: `You are restoring \`${title}\`.`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, restore it!"
  }).then((result) => {
    if (result.isConfirmed) {
      const item = document.querySelector('#restore-item-' + id);
      item.submit();
    }
  });
};


window.forceDeleteItem = function (id, title) {
  Swal.fire({
    title: "Are you sure?",
    text: `You are force deleteing \`${title}\`.`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, force delete it!"
  }).then((result) => {
    if (result.isConfirmed) {
      const item = document.querySelector('#force-delete-item-' + id);
      item.submit();
    }
  });
};

window.changeItem = function (id, title) {
  Swal.fire({
    title: "Are you sure?",
    text: `You are chnaging \`${title}\`.`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, change it!"
  }).then((result) => {
    if (result.isConfirmed) {
      const item = document.querySelector('#change-item-' + id);
      item.submit();
    }
  });
};

window.resetItem = function (id, title) {
  Swal.fire({
    title: "Are you sure?",
    text: `You are reseting password for \`${title}\`.`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, reset it!"
  }).then((result) => {
    if (result.isConfirmed) {
      const item = document.querySelector('#reset-item-' + id);
      item.submit();
    }
  });
};