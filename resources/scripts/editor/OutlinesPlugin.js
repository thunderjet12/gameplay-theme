// import { createElement } from "@wordpress/element";
console.log("theme js working")

import apiFetch from "@wordpress/api-fetch";
import { Component, createElement, useEffect } from "@wordpress/element";
import { registerPlugin }  from "@wordpress/plugins";
import { useSelect, useDispatch } from "@wordpress/data";
import { compose } from "@wordpress/compose";

// // let data = wp.data.select("core/editor").getEditorBlocks();
// // console.log( data )
// // let fs = require("fs");

// // fs.writeFile( testt , dictstring )
// //  new File( data , absolutePluginsDirPath +"/blueprints/test.json",
// //  	{ type: "application/json"},
// //  )

// // console.log( absolutePluginsDirPath );


//interface propsTypes {
//    name: string;
//}


//  class SbirdThemeBuilder extends Component {
//      constructor(props) {
//          super(props);
//          this.state = {
//             test: '',
//          }
//          console.log("test working 1");
//      }

         
    

//      componentDidUpdate () {

//         console.log("test working 2");
        

    //     let select = wp.data.select( 'core/editor' );
    //     let isSavingPost = select.isSavingPost();
    //     let isAutoSaving = select.isAutosavingPost();
    //     let didPostSaveRequestSucceed = select.didPostSaveRequestSucceed();
    //     if ( isSavingPost || isAutoSaving && didPostSaveRequestSucceed ) {
    //         console.log("isSavingPost && !isAutosavingPost && didPostSaveRequestSucceed");
    //         // unsubscribe();

    //         let currentPostId = wp.data.select("core/editor").getCurrentPostId();
    //         let currentPostTitle = wp.data.select("core/editor").getCurrentPost().title;
    //         let currentPostType = wp.data.select("core/editor").getCurrentPostType()
    //         let currentPostAttributes = wp.data.select("core/editor").getEditorBlocks();
    //         // wp.data.select( 'core/block-editor' ).getSettings()
    //         //bluePrint full
    //         let blueprintData = {
    //             currentPostId: currentPostId,
    //             currentPostTitle: currentPostTitle,
    //             currentPostType: currentPostType,
    //             postAttributes: currentPostAttributes,
    //         };

    //         console.log( blueprintData )

    //         apiFetch( { 
    //             path: '/blueprints/v2/blueprint',
    //             method: "POST",
    //             headers: { 
    //                 'Content-type': 'application/json',
    //                 'X-WP-Nonce': wpApiSettings.nonce
    //             },
    //             'credentials': 'same-origin',
    //             data: { blueprint: blueprintData }
    //         } )       
    //         .then( res => 
    //             console.log( res )
    //         ).catch( err => {
    //             console.log( err );
    //         } )

    //     }
    //  }
        
     
        // const unsubscribe = wp.data.subscribe( () => {
           
        // } )
                
        
 
    // render() {
    //     return 
    // }
// }

// const { settings, templateType, page, deviceType } = useSelect(
//     ( select ) => {
//         const {
//             getSettings,
//             getEditedPostType,
//             getPage,
//             __experimentalGetPreviewDeviceType,
//         } = select( 'core/edit-site' );
//         return {
//             settings: getSettings( setIsInserterOpen ),
//             templateType: getEditedPostType(),
//             page: getPage(),
//             deviceType: __experimentalGetPreviewDeviceType(),
//         };
//     },
//     []
// );




// console.log(settings, templateType, page, deviceType);

// function Effect( props: PluginSettings ) {

//     const Page = useSelect(
//         ( select ) => {
//             return select( 'core/edit-site' ).getPage();    
//         },
//         []
//     );
//     console.log( "test 3" );
//     return Page;
// }

// export default compose( Effect ) ( SbirdThemeBuilder );

function SbirdThemeBuilder (props){
    console.log("test working 1");
    let select = wp.data.select( 'core/editor' );
    let isSavingPost = select.isSavingPost();
    let isAutoSaving = select.isAutosavingPost();
    let didPostSaveRequestSucceed = select.didPostSaveRequestSucceed();
    if ( isSavingPost || isAutoSaving && didPostSaveRequestSucceed ) {
        console.log("isSavingPost && !isAutosavingPost && didPostSaveRequestSucceed");
        // unsubscribe();

        let currentPostId = wp.data.select("core/editor").getCurrentPostId();
        let currentPostTitle = wp.data.select("core/editor").getCurrentPost().title;
        let currentPostType = wp.data.select("core/editor").getCurrentPostType()
        let currentPostAttributes = wp.data.select("core/editor").getEditorBlocks();
         wp.data.select( 'core/block-editor' ).getSettings()
        //bluePrint full
        let blueprintData = {
            currentPostId: currentPostId,
            currentPostTitle: currentPostTitle,
            currentPostType: currentPostType,
            postAttributes: currentPostAttributes,
        };

        console.log( blueprintData )

        apiFetch( { 
            path: '/blueprints/v2/blueprint',
            method: "POST",
            headers: { 
                'Content-type': 'application/json',
                'X-WP-Nonce': wpApiSettings.nonce
            },
            'credentials': 'same-origin',
            data: { blueprint: blueprintData }
        } )       
        .then( res => 
            console.log( res )
        ).catch( err => {
            console.log( err );
        } )    
    }

    useEffect( () => {
        console.log( "effect work" )
    }, [ isSavingPost || isAutoSaving && didPostSaveRequestSucceed ] )

    return (
            <>
            </> 
            );
}

registerPlugin ( 'sbird-core-additions', {
    icon: "admin-appearance",
    render: SbirdThemeBuilder,
} )