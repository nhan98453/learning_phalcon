import React, { Component } from 'react';
import Popup from 'reactjs-popup';
export default class Product extends Component {
    constructor(props){
        super(props);
        this.state={
            listProduct:[],
            newProduct:{
                "name":'',
                "Category":'',
                "Brand":'',
            },
            editProduct:{
                "id":null,
                "name":null,
                "Category":null,
                "Brand":null,
            }
        }
    }
    componentWillMount(){
        const listProduct = JSON.parse(localStorage.getItem('product'));
        this.setState({listProduct})
    }
    handleSubmit = async (e) =>{
        e.preventDefault();
        const listProduct = this.state.listProduct;
        const newProduct = {...this.state.newProduct,id:listProduct.length.toString()};
        listProduct.push(newProduct);
        await this.setState({
            listProduct
        })
        console.log(this.state);
        localStorage.setItem("product",JSON.stringify(this.state.listProduct));
        return true;
    }
    removeProduct = async (e) => {
        let confirm= window.confirm("Delete product id= "+e.target.value);
        if(confirm){
            const listProduct = this.state.listProduct.filter(product =>{
                return product.id != e.target.value; 
            });
            await this.setState({listProduct});
            localStorage.setItem("product",JSON.stringify(this.state.listProduct));
        }
    }
    handleEditProduct = async e =>{
        let id = e.target.value;
        let confirm= window.confirm("Edit product id= "+id);
        if(confirm){
            let listProduct = this.state.listProduct;
            const editProduct = {...this.state.editProduct,id:id};
            const index = listProduct.findIndex(x => x.id === id)
            console.log(this.state.listProduct)
            listProduct[index] = {
                id : editProduct.id,
                name: editProduct.name?editProduct.name:listProduct[index].name,
                Category : editProduct.Category?editProduct.Category:listProduct[index].Category,
                Brand : editProduct.Brand?editProduct.Brand:listProduct[index].Brand,
            }
            await this.setState({listProduct});
            localStorage.setItem("product",JSON.stringify(this.state.listProduct));
        }
    }
    render() {
        return (
            <div style={{border:'2px solid green'}}>
                
                <Popup trigger={<button>Thêm Sản phẩm</button>} modal
                closeOnDocumentClick>
                    {close => (
                        <div>
                            <a className="close" onClick={close}>
                                &times;
                            </a>
                            <form onSubmit={this.handleSubmit}>
                                <table className="table table-hover">
                                    <thead><th >Thêm Sản phẩm</th></thead>
                                    <tbody>
                                        <tbody><input type='text' placeholder='Tên Sản phẩm' onChange={e => {this.setState({newProduct:{...this.state.newProduct,name:e.target.value}})}}/></tbody>
                                        <tbody><input type='text' placeholder='Category' onChange={e => {this.setState({newProduct:{...this.state.newProduct,Category:e.target.value}})}}/></tbody>
                                        <tbody><input type='text' placeholder='Brand' onChange={e => {this.setState({newProduct:{...this.state.newProduct,Brand:e.target.value}})}}/></tbody>
                                    </tbody>
                                </table>
                                <input type="submit" value="Thêm" />
                            </form>
                        </div>
                    )}
                </Popup>
                <table className="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Control</th>
                            </tr>
                    </thead>
                    <tbody>
                    {this.state.listProduct.map((product)=>{
                        return(
                            <tr key={product.id}>
                                <td>{product.id}</td>
                                <td>{product.name}</td>
                                <td>{product.Category}</td>
                                <td>{product.Brand}</td>
                                <td>
                                <Popup trigger={<button >Sửa</button>} modal>
                                {close => (
                                    <div>
                                        <table className="table table-hover" >
                                            <thead><th colspan="2" style={{textAlign:'center'}}>Sửa Sản Phẩm</th></thead>
                                            <tbody >
                                                <tbody>
                                                    <td>Tên Sản Phẩm:</td>
                                                    <td>
                                                    <input type='text' placeholder={product.name} onChange={e => {this.setState({editProduct:{...this.state.editProduct,name:e.target.value}})}}/>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>Category</td>
                                                    <td>
                                                        <input type='text' placeholder={product.Category} onChange={e => {this.setState({editProduct:{...this.state.editProduct,Category:e.target.value}})}}/>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>Brand</td>
                                                    <td>
                                                        <input type='text' placeholder={product.Brand} onChange={e => {this.setState({editProduct:{...this.state.editProduct,Brand:e.target.value}})}}/>
                                                    </td>
                                                </tbody>
                                            </tbody>
                                        </table>
                                        <button value={product.id} onClick={e => this.handleEditProduct(e) }>Sửa</button>
                                    </div>
                                )}
                            </Popup>
                                    <button type="button" onClick={this.removeProduct} value={product.id}>Xóa</button>
                                </td>
                            </tr>
                        )
                    })}
                    
                    </tbody>
                </table>
                
            </div>
        )
    }
}
  