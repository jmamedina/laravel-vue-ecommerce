import axiosClient from "../axios";

// Get current user data from the server / サーバーから現在のユーザーデータを取得する
export function getCurrentUser({ commit }, data) {
  return axiosClient.get('/user', data)
    .then(({ data }) => {
      commit('setUser', data);
      return data;
    })
}

// Log in user / ユーザーをログインする
export function login({ commit }, data) {
  return axiosClient.post('/login', data)
    .then(({ data }) => {
      commit('setUser', data.user);
      commit('setToken', data.token)
      return data;
    })
}

// Log out user / ユーザーをログアウトする
export function logout({ commit }) {
  return axiosClient.post('/logout')
    .then((response) => {
      commit('setToken', null)

      return response;
    })
}

// Get list of countries / 国のリストを取得する
export function getCountries({ commit }) {
  return axiosClient.get('countries')
    .then(({ data }) => {
      commit('setCountries', data)
    })
}

// Get list of orders / 注文のリストを取得する
export function getOrders({ commit, state }, { url = null, search = '', per_page, sort_field, sort_direction } = {}) {
  commit('setOrders', [true])
  url = url || '/orders'
  const params = {
    per_page: state.orders.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setOrders', [false, response.data])
    })
    .catch(() => {
      commit('setOrders', [false])
    })
}

// Get order details by ID / IDで注文の詳細を取得する
export function getOrder({ commit }, id) {
  return axiosClient.get(`/orders/${id}`)
}

// Get list of products / 商品のリストを取得する
export function getProducts({ commit, state }, { url = null, search = '', per_page, sort_field, sort_direction } = {}) {
  commit('setProducts', [true])
  url = url || '/products'
  const params = {
    per_page: state.products.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setProducts', [false, response.data])
    })
    .catch(() => {
      commit('setProducts', [false])
    })
}

// Get product details by ID / IDで商品の詳細を取得する
export function getProduct({ commit }, id) {
  return axiosClient.get(`/products/${id}`)
}

// Create a new product / 新しい商品を作成する
export function createProduct({ commit }, product) {
  if (product.images && product.images.length) {
    const form = new FormData();
    form.append('title', product.title);
    product.images.forEach(im => form.append('images[]', im))
    form.append('description', product.description || '');
    form.append('published', product.published ? 1 : 0);
    form.append('price', product.price);
    product = form;
  }
  return axiosClient.post('/products', product)
}

// Update existing product / 既存の商品を更新する
export function updateProduct({ commit }, product) {
  const id = product.id
  if (product.images && product.images.length) {
    const form = new FormData();
    form.append('id', product.id);
    form.append('title', product.title);
    product.images.forEach(im => form.append(`images[${im.id}]`, im))
    if (product.deleted_images) {
      product.deleted_images.forEach(id => form.append('deleted_images[]', id))
    }
    for (let id in product.image_positions) {
      form.append(`image_positions[${id}]`, product.image_positions[id])
    }
    form.append('description', product.description || '');
    form.append('published', product.published ? 1 : 0);
    form.append('price', product.price);
    form.append('_method', 'PUT');
    product = form;
  } else {
    product._method = 'PUT'
  }
  return axiosClient.post(`/products/${id}`, product)
}

// Delete product by ID / IDで商品を削除する
export function deleteProduct({ commit }, id) {
  return axiosClient.delete(`/products/${id}`)
}

// Get list of users / ユーザーのリストを取得する
export function getUsers({ commit, state }, { url = null, search = '', per_page, sort_field, sort_direction } = {}) {
  commit('setUsers', [true])
  url = url || '/users'
  const params = {
    per_page: state.users.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setUsers', [false, response.data])
    })
    .catch(() => {
      commit('setUsers', [false])
    })
}

// Create a new user / 新しいユーザーを作成する
export function createUser({ commit }, user) {
  return axiosClient.post('/users', user)
}

// Update existing user / 既存のユーザーを更新する
export function updateUser({ commit }, user) {
  return axiosClient.put(`/users/${user.id}`, user)
}

// Get list of customers / 顧客のリストを取得する
export function getCustomers({ commit, state }, { url = null, search = '', per_page, sort_field, sort_direction } = {}) {
  commit('setCustomers', [true])
  url = url || '/customers'
  const params = {
    per_page: state.customers.limit,
  }
  return axiosClient.get(url, {
    params: {
      ...params,
      search, per_page, sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setCustomers', [false, response.data])
    })
    .catch(() => {
      commit('setCustomers', [false])
    })
}

// Get customer details by ID / IDで顧客の詳細を取得する
export function getCustomer({ commit }, id) {
  return axiosClient.get(`/customers/${id}`)
}

// Create a new customer / 新しい顧客を作成する
export function createCustomer({ commit }, customer) {
  return axiosClient.post('/customers', customer)
}

// Update existing customer / 既存の顧客を更新する
export function updateCustomer({ commit }, customer) {
  return axiosClient.put(`/customers/${customer.id}`, customer)
}

// Delete customer by ID / IDで顧客を削除する
export function deleteCustomer({ commit }, customer) {
  return axiosClient.delete(`/customers/${customer.id}`)
}

// Get list of categories / カテゴリーのリストを取得する
export function getCategories({ commit, state }, { sort_field, sort_direction } = {}) {
  commit('setCategories', [true])
  return axiosClient.get('/categories', {
    params: {
      sort_field, sort_direction
    }
  })
    .then((response) => {
      commit('setCategories', [false, response.data])
    })
    .catch(() => {
      commit('setCategories', [false])
    })
}

// Create a new category / 新しいカテゴリーを作成する
export function createCategory({ commit }, category) {
  return axiosClient.post('/categories', category)
}

// Update existing category / 既存のカテゴリーを更新する
export function updateCategory({ commit }, category) {
  return axiosClient.put(`/categories/${category.id}`, category)
}

// Delete category by ID / IDでカテゴリーを削除する
export function deleteCategory({ commit }, category) {
  return axiosClient.delete(`/categories/${category.id}`)
}
