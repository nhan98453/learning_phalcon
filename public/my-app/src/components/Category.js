import React, { Component } from 'react'
import Popup from 'reactjs-popup';

export default class Category extends Component {
    constructor(props){
        super(props);
        this.state={
            listCategory:[],
            newCategoryName:'',
            editCategory:{
                name:null
            }
        }
    }
    componentWillMount(){
        const listCategory = JSON.parse(localStorage.getItem('category'));
        this.setState({listCategory})
    }
    handleSubmit = async (e) =>{
        e.preventDefault();
        const listCategory = this.state.listCategory;
        listCategory.push({
            id:listCategory.length,
            name:this.state.newCategoryName
        });
        await this.setState({
            listCategory
        })
        console.log(this.state);
        localStorage.setItem("category",JSON.stringify(this.state.listCategory));
    }
    removeCategory = (e) => {
        let confirm= window.confirm("Delete category id= "+e.target.value);
        if(confirm){
            const listCategory = this.state.listCategory.filter(category =>{
                return category.id != e.target.value; 
            });
            this.setState({listCategory});
            localStorage.setItem("category",JSON.stringify(this.state.listCategory));
        }
    }
    handleEditCategory = e =>{
        let id = e.target.value;
        let confirm= window.confirm("Edit Category id= "+id);
        if(confirm){
            let listCategory = this.state.listCategory;
            const editCategory = {...this.state.editCategory,id:id};
            const index = listCategory.findIndex(x => x.id === id)
            console.log(editCategory)
            listCategory[index] = {
                id : editCategory.id,
                name: editCategory.name?editCategory.name:listCategory[index].name,
            }
            this.setState({listCategory});
            localStorage.setItem("category",JSON.stringify(this.state.listCategory));
        }
    }
    render() {
        return (
            <div>
                <Popup trigger={<button>Thêm Category</button>} position="bottom left">
                    {close => (
                        <div>
                            <a className="close" onClick={close}>
                                &times;
                            </a>
                            <form onSubmit={this.handleSubmit}>
                                <table className="table table-hover">
                                    <thead><th >Thêm Category</th></thead>
                                    <tbody>
                                        <tbody><input type='text' placeholder='Tên Category' onChange={e => {this.setState({newCategoryName:e.target.value})}}/></tbody>
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
                    {this.state.listCategory.map((category)=>{
                        return(
                            <tr>
                                <td>{category.id}</td>
                                <td>{category.name}</td>
                                <td>
                                <Popup trigger={<button >Sửa</button>} modal>
                                {close => (
                                    <div>
                                        <table className="table table-hover" >
                                            <thead><th colspan="2" style={{textAlign:'center'}}>Sửa Sản Phẩm</th></thead>
                                            <tbody >
                                                <tbody>
                                                    <td>Tên Category</td>
                                                    <td>
                                                    <input type='text' placeholder={category.name} onChange={e => {this.setState({editCategory:{...this.state.editCategory,name:e.target.value}})}}/>
                                                    </td>
                                                </tbody>
                                            </tbody>
                                        </table>
                                        <button value={category.id} onClick={e => this.handleEditCategory(e) }>Sửa</button>
                                    </div>
                                )}
                            </Popup>
                                    <button type="button" onClick={this.removeCategory} value={category.id}>Xóa</button>
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
