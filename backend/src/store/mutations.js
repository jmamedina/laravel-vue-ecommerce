export function setUser(state, user){
    state.user.data = user;
}

export function setToken(state, token){
    state.user.token = token;
    if(token){
        sessionStorage.setItem('TOKEN', token);
    } else {
        sessionStorage.removeItem('TOKEN')
    }
}

export function setProducts(state, [loading, response = null]){
  if(response){
    state.products = {
     data : response.data,
     links : response.meta.links,
     total : response.meta.total,
     from : response.meta.from,
     to : response.meta.to,
     page : response.meta.current_page,
     limit : response.meta.per_page,
    }

    state.products.loading = loading;

  }
}

export function setUsers(state, [loading, data = null]) {

  if (data) {
    state.users = {
      ...state.users,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.products.loading = loading;
}


export function setOrders(state, [loading, response = null]){
  if(response){
    state.orders = {
     data : response.data,
     links : response.meta.links,
     total : response.meta.total,
     from : response.meta.from,
     to : response.meta.to,
     page : response.meta.current_page,
     limit : response.meta.per_page,
    }

    state.orders.loading = loading;

  }
}

// export function showToast(state, message){
//   state.toast.show = true;
//   state.toast.message = message;
// }