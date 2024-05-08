// Set user data in the state / 状態にユーザーデータを設定する
export function setUser(state, user) {
  state.user.data = user;
}

// Set authentication token in the state / 状態に認証トークンを設定する
export function setToken(state, token) {
  state.user.token = token;
  if (token) {
    sessionStorage.setItem('TOKEN', token);
  } else {
    sessionStorage.removeItem('TOKEN')
  }
}

// Set products data in the state / 状態に商品データを設定する
export function setProducts(state, [loading, data = null]) {

  if (data) {
    state.products = {
      ...state.products,
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

// Set users data in the state / 状態にユーザーデータを設定する
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
  state.users.loading = loading;
}

// Set customers data in the state / 状態に顧客データを設定する
export function setCustomers(state, [loading, data = null]) {

  if (data) {
    state.customers = {
      ...state.customers,
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

// Set orders data in the state / 状態に注文データを設定する
export function setOrders(state, [loading, data = null]) {

  if (data) {
    state.orders = {
      ...state.orders,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.orders.loading = loading;
}

// Show toast message / トーストメッセージを表示する
export function showToast(state, message) {
  state.toast.show = true;
  state.toast.message = message;
}

// Hide toast message / トーストメッセージを非表示にする
export function hideToast(state) {
  state.toast.show = false;
  state.toast.message = '';
}

// Set countries data in the state / 状態に国のデータを設定する
export function setCountries(state, countries) {
  state.countries = countries.data;
}

// Set categories data in the state / 状態にカテゴリーのデータを設定する
export function setCategories(state, [loading, data = null]) {

  if (data) {
    state.categories = {
      ...state.categories,
      data: data.data,
    }
  }

  state.categories.loading = loading;
}
