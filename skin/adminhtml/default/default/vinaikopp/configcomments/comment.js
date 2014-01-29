
//delete console.log;
var commentsApp = angular.module('comments', ['ngAnimate']);
commentsApp.value('initData', {});

commentsApp
    .directive('vkInitComments', function(initData) {
        return {
            restrict: 'E',
            link: function(scope, elements, attrs) {
                var data = {author: '- unknown -', comments: {}};
                try {
                    data = eval('(' + elements[0].innerHTML + ')');
                    elements[0].innerHTML = '';
                } catch (e) {}
                angular.extend(initData, data);
            }
        }
    })
    .directive('vkStopEvent', function() {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                element.bind(attr.vkStopEvent, function(e) {
                    e.stopPropagation();
                })
            }
        }
    })
    .factory('Comments', function($http, $timeout, initData) {
        var factory = {
            comments: initData.comments,
            newComment: {},
            editComment: {},
            updateStatus: {},
            getComments: function(path) {
                return this.comments[path] ? this.comments[path] : [];
            },
            getCurrent: function(idx) {
                var path = this.currentPath;
                var idxProvided = typeof idx === 'number';
                var empty = idxProvided ? {} : [];
                if (! this.comments[path]) return empty;
                else if (! idxProvided) return this.comments[path];
                
                if (! this.comments[path][idx]) return empty;
                else return this.comments[path][idx];
            },
            setPath: function(path) {
                if (path != this.currentPath) {
                    this.newComment.text = '';
                    this.updateStatus.ok = false;
                    this.updateStatus.fail = false;
                }
                this.currentPath = path;
            },
            getCurrentPath: function() {
                return this.currentPath;
            },
            addNew: function() {
                var path = this.currentPath;
                if (this.newComment.text.length > 0 && path.length > 0) {
                    if (! this.comments[path]) this.comments[path] = [];
                    
                    this.newComment.author = initData.author;
                    this.comments[path].push(angular.copy(this.newComment));
                    this.newComment.text = '';
                    this.persist();
                }
            },
            update: function(idx) {
                if (this.editComment != this.getCurrent(idx)) {
                    var path = this.currentPath;
                    this.editComment.author = initData.author;
                    this.comments[path][idx] = angular.copy(this.editComment);
                    this.persist();
                }
                this.editComment.text = '';  
            },
            remove: function(idx) {
                var path = this.currentPath;
                if (this.comments[path]) {
                    this.comments[path].splice(idx, 1);
                    this.persist();
                }
            },
            persist: function() {
                var path = this.currentPath;
                if (this.comments[path]) {
                    this.resetUpdateStatus();
                    $http({
                            url: initData.updateUrl,
                            method: 'POST',
                            data: {
                                path: path,
                                comments: this.comments[path]
                            }
                        })
                        .success(function(data, status, headers, config) {
                            this.updateStatus.ok = data.ok && data.ok.length;
                            this.updateStatus.fail = data.fail && data.fail.length;
                            this.updateStatus.error = data.error ? data.error : false;
                            $timeout(this.resetUpdateStatus.bind(this), 3000);
                            
                        }.bind(this))
                        .error(function(data, status, headers, config) {
                            console.log('Update error!');
                            this.updateStatus.error = 'Some error occurrred during the update.';
                            $timeout(this.resetUpdateStatus.bind(this), 10000);
                        }.bind(this));
                }
            },
            resetUpdateStatus: function() {
                this.updateStatus.ok = false;
                this.updateStatus.fail = false;
                this.updateStatus.error = false;
            },
            currentPath: ''
        };
        return factory;
    })
    .factory('Popup', function() {
        var factory = {
            visible: false,
            show: function(e) {
                // Find the matching config value table cell
                var rel = $(e.fromElement);
                if (! rel) rel = e.relatedTarget;
                if (! rel.match('td.value') && ! (rel = rel.up('td.value')))
                    return;
    
                // Set visibility flag
                this.visible = true;
                
                // Move to position
                var t = this.offset(angular.element(rel));
                $('vinaikopp-comments-popup').setStyle({
                    left: (t.left - 80)+'px',
                    top: t.top+'px',
                    width: (rel.getWidth()+30) + 'px'
                });
                return true;
            },
            hide: function(e) {
                return this.visible = false;
            },
            // http://cvmlrobotics.blogspot.de/2013/03/angularjs-get-element-offset-position.html
            offset: function (elm) {
                try { return elm.offset(); } catch (e) {}
                var rawDom = elm[0];
                var _x = 0;
                var _y = 0;
                var body = document.documentElement || document.body;
                var scrollX = window.pageXOffset || body.scrollLeft;
                var scrollY = window.pageYOffset || body.scrollTop;
                _x = rawDom.getBoundingClientRect().left + scrollX;
                _y = rawDom.getBoundingClientRect().top + scrollY;
                return { left: _x, top: _y };
            }
        };
        return factory;
    })
    .controller('FieldCtrl', function($scope, Comments, Popup) {
        var commentService = Comments;
        var popupService = Popup;
        
        $scope.getComments = function(path) {
            return commentService.getComments(path);
        };
        $scope.showPopup = function($event, path) {
            commentService.setPath(path);
            popupService.show($event);
        }
    })
    .controller('PopupCtrl', function ($scope, Comments, Popup, initData) {
        var commentService = Comments;
        var popupService = Popup;
        var inlineEditEnabled = false;
        
        $scope.newComment = commentService.newComment;
        $scope.editComment = commentService.editComment;
        $scope.updateStatus = commentService.updateStatus
        
        $scope.getCurrentComments = function() {
            return commentService.getCurrent();
        };
        $scope.getCurrentPath = function() {
            return commentService.getCurrentPath();
        };
        $scope.hidePopup = function() {
            popupService.hide();
        }
        $scope.isPopupVisible = function() {
            return popupService.visible;
        }
        $scope.addNew = function() {
            commentService.addNew();
        }
        $scope.remove = function(key) {
            commentService.remove(key);
        }
        $scope.getCurrentAuthor = function() {
            return initData.author;
        }
        $scope.isInlineEdit = function(idx) {
            return inlineEditEnabled === idx+1;
        }
        $scope.startEdit = function(idx) {
            $scope.editComment.text = commentService.getCurrent()[idx].text;
            inlineEditEnabled = idx+1;
        }
        $scope.stopEdit = function() {
            if (inlineEditEnabled) {
                commentService.update(inlineEditEnabled-1);
                inlineEditEnabled = false;
            }
        }
        $scope.isTab = function($event) {
            return $event.keyCode && $event.keyCode == 9; 
        }
    });