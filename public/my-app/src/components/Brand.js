import React, { Component } from 'react'
import Popup from 'reactjs-popup';

export default class Brand extends Component {
    constructor(props){
        super(props);
        this.state={
            listBrand:[],
            newBrandName:'',
            editBrand:{
                name:null
            }
        }
    }
    componentWillMount(){
        const listBrand = JSON.parse(localStorage.getItem('brand'));
        this.setState({listBrand})
    }
    handleSubmit = async (e) =>{
        e.preventDefault();
        const listBrand = this.state.listBrand;
        listBrand.push({
            id:listBrand.length,
            name:this.state.newBrandName
        });
        await this.setState({
            listBrand
        })
        console.log(this.state);
        localStorage.setItem("brand",JSON.stringify(this.state.listBrand));
    }
    removeBrand = (e) => {
        let confirm= window.confirm("Delete brand id= "+e.target.value);
        if(confirm){
            const listBrand = this.state.listBrand.filter(category =>{
                return category.id != e.target.value; 
            });
            this.setState({listBrand});
            localStorage.setItem("brand",JSON.stringify(this.state.listBrand));
        }
    }
    handleEditBrand = e =>{
        let id = e.target.value;
        let confirm= window.confirm("Edit Brand id= "+id);
        if(confirm){
            let listBrand = this.state.listBrand;
            const editBrand = {...this.state.editBrand,id:id};
            const index = listBrand.findIndex(x => x.id === id)
            console.log(editBrand)
            listBrand[index] = {
                id : editBrand.id,
                name: editBrand.name?editBrand.name:listBrand[index].name,
            }
            this.setState({listBrand});
            localStorage.setItem("brand",JSON.stringify(this.state.listBrand));
        }
    }
    render() {
        return (
            <div>
                <Popup trigger={<button>Thêm Brand</button>} position="bottom left">
                    {close => (
                        <div>
                            <a className="close" onClick={close}>
                                &times;
                            </a>
                            <form onSubmit={this.handleSubmit}>
                                <table className="table table-hover">
                                    <thead><th >Thêm Brand</th></thead>
                                    <tbody>
                                        <tbody><input type='text' placeholder='Tên Brand' onChange={e => {this.setState({newBrandName:e.target.value})}}/></tbody>
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
                            <th>Control</th>
                        </tr>
                    </thead>
                    <tbody>
                    {this.state.listBrand.map((Brand)=>{
                        return(
                            <tr>
                                <td>{Brand.id}</td>
                                <td>{Brand.name}</td>
                                <td>
                                <Popup trigger={<button >Sửa</button>} modal>
                                {close => (
                                    <div>
                                        <table className="table table-hover" >
                                            <thead><th colspan="2" style={{textAlign:'center'}}>Sửa Sản Phẩm</th></thead>
                                            <tbody >
                                                <tbody>
                                                    <td>Tên Brand</td>
                                                    <td>
                                                    <input type='text' placeholder={Brand.name} onChange={e => {this.setState({editBrand:{...this.state.editBrand,name:e.target.value}})}}/>
                                                    </td>
                                                </tbody>
                                            </tbody>
                                        </table>
                                        <button value={Brand.id} onClick={e => this.handleEditBrand(e) }>Sửa</button>
                                    </div>
                                )}
                            </Popup>
                                    <button type="button" onClick={this.removeBrand} value={Brand.id}>Xóa</button>
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
